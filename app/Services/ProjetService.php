<?php

namespace App\Services;

use App\Models\Projet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProjetService extends BaseService
{
    /**
     * List projets with filters and pagination
     * 
     * @param array $filters Search filters
     * @param int $page Page number
     * @param int $limit Items per page
     * @return array{projets: Collection<int, Projet>, total: int, page: int, limit: int, search: array}
     */
    public function list(array $filters, int $page, int $limit): array
    {
        $query = Projet::query()->with('societe');

        $query = $this->applyFilters($query, $filters);

        $total = $query->count();
        $offset = $page * $limit;

        $projets = $query->orderBy('ref', 'DESC')
            ->skip($offset)
            ->take($limit)
            ->get();

        return [
            'projets' => $projets,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'search' => $filters,
        ];
    }

    /**
     * Apply search filters to query
     */
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        if (!empty($filters['all'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('ref', 'like', $this->like($filters['all']))
                    ->orWhere('title', 'like', $this->like($filters['all']));
            });
        }

        if (!empty($filters['ref'])) {
            $query->where('ref', 'like', $this->like($filters['ref']));
        }

        if (!empty($filters['title'])) {
            $query->where('title', 'like', $this->like($filters['title']));
        }

        if (!empty($filters['status'])) {
            $query->where('fk_statut', $filters['status']);
        }

        return $query;
    }
}
