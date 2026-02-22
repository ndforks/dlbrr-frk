<?php

namespace App\Services;

use App\Models\EcmDirectory;
use App\Models\EcmFiles;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class EcmService
{
    /**
     * Get paginated list of ECM directories
     *
     * @param array $search Search parameters
     * @param string $sortfield Field to sort by
     * @param string $sortorder Sort order (ASC/DESC)
     * @param int $limit Number of records per page
     * @param int $offset Starting offset
     * @param int $entity Entity ID
     * @return array Array containing directories and total count
     */
    public function getDirectories(
        array $search = [],
        string $sortfield = 'label',
        string $sortorder = 'ASC',
        int $limit = 25,
        int $offset = 0,
        int $entity = 1
    ): array {
        $query = EcmDirectory::query()
            ->where('entity', $entity);

        // Apply search filters
        if (!empty($search['label'])) {
            $query->where('label', 'like', '%' . $search['label'] . '%');
        }

        if (!empty($search['description'])) {
            $query->where('description', 'like', '%' . $search['description'] . '%');
        }

        if (isset($search['fk_parent'])) {
            $query->where('fk_parent', $search['fk_parent']);
        }

        // Get total count
        $total = $query->count();

        // Apply sorting and pagination
        $query->orderBy($sortfield, $sortorder)
              ->skip($offset)
              ->take($limit);

        $directories = $query->get();

        return [
            'directories' => $directories,
            'total' => $total,
        ];
    }

    /**
     * Get paginated list of ECM files
     *
     * @param array $search Search parameters
     * @param string $sortfield Field to sort by
     * @param string $sortorder Sort order (ASC/DESC)
     * @param int $limit Number of records per page
     * @param int $offset Starting offset
     * @param int $entity Entity ID
     * @return array Array containing files and total count
     */
    public function getFiles(
        array $search = [],
        string $sortfield = 'filename',
        string $sortorder = 'ASC',
        int $limit = 25,
        int $offset = 0,
        int $entity = 1
    ): array {
        $query = EcmFiles::query()
            ->where('entity', $entity);

        // Apply search filters
        if (!empty($search['filename'])) {
            $query->where('filename', 'like', '%' . $search['filename'] . '%');
        }

        if (!empty($search['label'])) {
            $query->where('label', 'like', '%' . $search['label'] . '%');
        }

        if (isset($search['src_object_type'])) {
            $query->where('src_object_type', $search['src_object_type']);
        }

        if (isset($search['src_object_id'])) {
            $query->where('src_object_id', $search['src_object_id']);
        }

        // Get total count
        $total = $query->count();

        // Apply sorting and pagination
        $query->orderBy($sortfield, $sortorder)
              ->skip($offset)
              ->take($limit);

        $files = $query->get();

        return [
            'files' => $files,
            'total' => $total,
        ];
    }

    /**
     * Get directory by ID
     *
     * @param int $id Directory ID
     * @return EcmDirectory|null
     */
    public function getDirectoryById(int $id): ?EcmDirectory
    {
        return EcmDirectory::find($id);
    }

    /**
     * Get file by ID
     *
     * @param int $id File ID
     * @return EcmFiles|null
     */
    public function getFileById(int $id): ?EcmFiles
    {
        return EcmFiles::find($id);
    }

    /**
     * Get files in a directory
     *
     * @param int $directoryId Directory ID
     * @return Collection
     */
    public function getFilesInDirectory(int $directoryId): Collection
    {
        return EcmFiles::where('fk_directory', $directoryId)
            ->orderBy('filename', 'ASC')
            ->get();
    }

    /**
     * Get files for an object
     *
     * @param string $objectType Object type (e.g., 'product', 'invoice')
     * @param int $objectId Object ID
     * @return Collection
     */
    public function getFilesForObject(string $objectType, int $objectId): Collection
    {
        return EcmFiles::where('src_object_type', $objectType)
            ->where('src_object_id', $objectId)
            ->orderBy('filename', 'ASC')
            ->get();
    }

    /**
     * Create a new directory
     *
     * @param array $data Directory data
     * @return EcmDirectory
     */
    public function createDirectory(array $data): EcmDirectory
    {
        return EcmDirectory::create($data);
    }

    /**
     * Create a new file record
     *
     * @param array $data File data
     * @return EcmFiles
     */
    public function createFile(array $data): EcmFiles
    {
        return EcmFiles::create($data);
    }

    /**
     * Update a directory
     *
     * @param int $id Directory ID
     * @param array $data Updated data
     * @return bool
     */
    public function updateDirectory(int $id, array $data): bool
    {
        $directory = EcmDirectory::findOrFail($id);
        return $directory->update($data);
    }

    /**
     * Update a file record
     *
     * @param int $id File ID
     * @param array $data Updated data
     * @return bool
     */
    public function updateFile(int $id, array $data): bool
    {
        $file = EcmFiles::findOrFail($id);
        return $file->update($data);
    }

    /**
     * Delete a directory
     *
     * @param int $id Directory ID
     * @return bool
     */
    public function deleteDirectory(int $id): bool
    {
        $directory = EcmDirectory::findOrFail($id);
        return $directory->delete();
    }

    /**
     * Delete a file record
     *
     * @param int $id File ID
     * @return bool
     */
    public function deleteFile(int $id): bool
    {
        $file = EcmFiles::findOrFail($id);
        return $file->delete();
    }

    /**
     * Get directory tree
     *
     * @param int|null $parentId Parent directory ID (null for root)
     * @param int $entity Entity ID
     * @return array
     */
    public function getDirectoryTree(?int $parentId = null, int $entity = 1): array
    {
        $directories = EcmDirectory::where('entity', $entity)
            ->where('fk_parent', $parentId ?? 0)
            ->orderBy('label', 'ASC')
            ->get();

        $tree = [];
        foreach ($directories as $directory) {
            $node = $directory->toArray();
            $node['children'] = $this->getDirectoryTree($directory->rowid, $entity);
            $tree[] = $node;
        }

        return $tree;
    }
}
