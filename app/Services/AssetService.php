<?php

namespace App\Services;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Builder;

class AssetService extends BaseService
{
    public function list(array $filters, int $page, int $limit): array
    {
        $query = Asset::query();
        $query = $this->applyFilters($query, $filters);

        $total = $query->count();
        $offset = $page * $limit;

        $assets = $query->orderBy('ref', 'DESC')
            ->skip($offset)
            ->take($limit)
            ->get();

        return [
            'assets' => $assets,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'search' => $filters,
        ];
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        if (!empty($filters['all'])) {
            $query->where('ref', 'like', $this->like($filters['all']));
        }

        return $query;
    }
}
