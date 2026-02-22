<?php

namespace App\Services;

use App\Models\Bookmark;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BookmarkService
{
    /**
     * Get paginated list of bookmarks
     *
     * @param array $search Search parameters
     * @param string $sortfield Field to sort by
     * @param string $sortorder Sort order (ASC/DESC)
     * @param int $limit Number of records per page
     * @param int $offset Starting offset
     * @param int|null $userId Optional user ID filter
     * @return array Array containing bookmarks and total count
     */
    public function getList(
        array $search = [],
        string $sortfield = 'position',
        string $sortorder = 'ASC',
        int $limit = 25,
        int $offset = 0,
        ?int $userId = null
    ): array {
        $query = Bookmark::query()
            ->select([
                'llx_bookmark.rowid',
                'llx_bookmark.dateb',
                'llx_bookmark.fk_user',
                'llx_bookmark.url',
                'llx_bookmark.target',
                'llx_bookmark.title',
                'llx_bookmark.favicon',
                'llx_bookmark.position',
                'llx_user.login',
                'llx_user.lastname',
                'llx_user.firstname'
            ])
            ->leftJoin('llx_user', 'llx_bookmark.fk_user', '=', 'llx_user.rowid');

        // Filter by user if specified
        if ($userId !== null) {
            $query->where('llx_bookmark.fk_user', $userId);
        }

        // Apply search filters
        if (!empty($search['title'])) {
            $query->where('llx_bookmark.title', 'like', '%' . $search['title'] . '%');
        }

        if (!empty($search['url'])) {
            $query->where('llx_bookmark.url', 'like', '%' . $search['url'] . '%');
        }

        // Get total count
        $total = $query->count();

        // Apply sorting and pagination
        $sortfield = str_replace('b.', 'llx_bookmark.', $sortfield);
        $sortfield = str_replace('u.', 'llx_user.', $sortfield);
        
        $query->orderBy($sortfield, $sortorder)
              ->skip($offset)
              ->take($limit);

        $bookmarks = $query->get();

        return [
            'bookmarks' => $bookmarks,
            'total' => $total,
        ];
    }

    /**
     * Get a single bookmark by ID
     *
     * @param int $id Bookmark ID
     * @return Bookmark|null
     */
    public function getById(int $id): ?Bookmark
    {
        return Bookmark::with('user')->find($id);
    }

    /**
     * Get bookmarks for a specific user
     *
     * @param int $userId User ID
     * @return Collection
     */
    public function getByUser(int $userId): Collection
    {
        return Bookmark::where('fk_user', $userId)
            ->orderBy('position', 'ASC')
            ->get();
    }

    /**
     * Create a new bookmark
     *
     * @param array $data Bookmark data
     * @return Bookmark
     */
    public function create(array $data): Bookmark
    {
        // Set default position if not provided
        if (!isset($data['position'])) {
            $maxPosition = Bookmark::max('position');
            $data['position'] = ($maxPosition ?? 0) + 1;
        }

        return Bookmark::create($data);
    }

    /**
     * Update an existing bookmark
     *
     * @param int $id Bookmark ID
     * @param array $data Updated data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $bookmark = Bookmark::findOrFail($id);
        return $bookmark->update($data);
    }

    /**
     * Delete a bookmark
     *
     * @param int $id Bookmark ID
     * @return bool
     */
    public function delete(int $id): bool
    {
        $bookmark = Bookmark::findOrFail($id);
        return $bookmark->delete();
    }

    /**
     * Update bookmark positions
     *
     * @param array $positions Array of bookmark IDs and their new positions
     * @return bool
     */
    public function updatePositions(array $positions): bool
    {
        DB::beginTransaction();
        
        try {
            foreach ($positions as $id => $position) {
                Bookmark::where('rowid', $id)->update(['position' => $position]);
            }
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
