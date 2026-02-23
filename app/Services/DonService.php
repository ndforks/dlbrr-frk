<?php

namespace App\Services;

use App\Models\Don;
use Illuminate\Database\Eloquent\Builder;

class DonService extends BaseService
{
    public function list(array $filters, int $page, int $limit): array
    {
        $query = Don::query()->with('societe');
        $query = $this->applyFilters($query, $filters);

        $total = $query->count();
        $offset = $page * $limit;

        $dons = $query->orderBy('datedon', 'DESC')
            ->skip($offset)
            ->take($limit)
            ->get();

        return [
            'dons' => $dons,
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
