<?php

namespace App\Services;

use App\Models\HrmPosition;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class HrmService
{
    /**
     * Get paginated list of HR positions
     *
     * @param array $search Search parameters
     * @param string $sortfield Field to sort by
     * @param string $sortorder Sort order (ASC/DESC)
     * @param int $limit Number of records per page
     * @param int $offset Starting offset
     * @param int $entity Entity ID
     * @return array Array containing positions and total count
     */
    public function getPositions(
        array $search = [],
        string $sortfield = 'label',
        string $sortorder = 'ASC',
        int $limit = 25,
        int $offset = 0,
        int $entity = 1
    ): array {
        $query = HrmPosition::query()
            ->where('entity', $entity);

        // Apply search filters
        if (!empty($search['ref'])) {
            $query->where('ref', 'like', '%' . $search['ref'] . '%');
        }

        if (!empty($search['label'])) {
            $query->where('label', 'like', '%' . $search['label'] . '%');
        }

        if (isset($search['status'])) {
            $query->where('status', $search['status']);
        }

        // Get total count
        $total = $query->count();

        // Apply sorting and pagination
        $query->orderBy($sortfield, $sortorder)
              ->skip($offset)
              ->take($limit);

        $positions = $query->get();

        return [
            'positions' => $positions,
            'total' => $total,
        ];
    }

    /**
     * Get a single position by ID
     *
     * @param int $id Position ID
     * @return HrmPosition|null
     */
    public function getPositionById(int $id): ?HrmPosition
    {
        return HrmPosition::find($id);
    }

    /**
     * Get positions by status
     *
     * @param int $status Status (0=draft, 1=active, 9=cancelled)
     * @param int $entity Entity ID
     * @return Collection
     */
    public function getPositionsByStatus(int $status, int $entity = 1): Collection
    {
        return HrmPosition::where('status', $status)
            ->where('entity', $entity)
            ->orderBy('label', 'ASC')
            ->get();
    }

    /**
     * Create a new position
     *
     * @param array $data Position data
     * @return HrmPosition
     */
    public function createPosition(array $data): HrmPosition
    {
        return HrmPosition::create($data);
    }

    /**
     * Update an existing position
     *
     * @param int $id Position ID
     * @param array $data Updated data
     * @return bool
     */
    public function updatePosition(int $id, array $data): bool
    {
        $position = HrmPosition::findOrFail($id);
        return $position->update($data);
    }

    /**
     * Delete a position
     *
     * @param int $id Position ID
     * @return bool
     */
    public function deletePosition(int $id): bool
    {
        $position = HrmPosition::findOrFail($id);
        return $position->delete();
    }

    /**
     * Get employees in a position
     *
     * @param int $positionId Position ID
     * @return Collection
     */
    public function getEmployeesInPosition(int $positionId): Collection
    {
        return DB::table('llx_user')
            ->where('fk_position', $positionId)
            ->where('statut', 1) // Active users only
            ->orderBy('lastname', 'ASC')
            ->get();
    }

    /**
     * Assign user to position
     *
     * @param int $userId User ID
     * @param int $positionId Position ID
     * @return bool
     */
    public function assignUserToPosition(int $userId, int $positionId): bool
    {
        return DB::table('llx_user')
            ->where('rowid', $userId)
            ->update(['fk_position' => $positionId]);
    }

    /**
     * Get position statistics
     *
     * @param int $entity Entity ID
     * @return array
     */
    public function getStatistics(int $entity = 1): array
    {
        $totalPositions = HrmPosition::where('entity', $entity)->count();
        $activePositions = HrmPosition::where('entity', $entity)
            ->where('status', 1)
            ->count();
        $draftPositions = HrmPosition::where('entity', $entity)
            ->where('status', 0)
            ->count();

        return [
            'total' => $totalPositions,
            'active' => $activePositions,
            'draft' => $draftPositions,
        ];
    }
}
