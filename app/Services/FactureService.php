<?php

namespace App\Services;

use App\Models\Facture;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class FactureService extends BaseService
{
    /**
     * List factures with filters and pagination
     * 
     * @param array $filters Search filters
     * @param int $page Page number
     * @param int $limit Items per page
     * @return array{factures: Collection<int, Facture>, total: int, page: int, limit: int, search: array}
     */
    public function list(array $filters, int $page, int $limit): array
    {
        $query = Facture::query()->with('societe');

        $query = $this->applyFilters($query, $filters);

        $total = $query->count();
        $offset = $page * $limit;

        $factures = $query->orderBy('datef', 'DESC')
            ->skip($offset)
            ->take($limit)
            ->get();

        return [
            'factures' => $factures,
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
                    ->orWhere('ref_client', 'like', $this->like($filters['all']));
            });
        }

        if (!empty($filters['ref'])) {
            $query->where('ref', 'like', $this->like($filters['ref']));
        }

        if (!empty($filters['societe'])) {
            $query->whereHas('societe', function ($q) use ($filters) {
                $q->where('nom', 'like', $this->like($filters['societe']));
            });
        }

        if (!empty($filters['status'])) {
            $query->where('fk_statut', $filters['status']);
        }

        return $query;
    }
}
