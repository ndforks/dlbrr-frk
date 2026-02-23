<?php

namespace App\Services;

use App\Models\Bom;
use Illuminate\Database\Eloquent\Builder;

class BomService extends BaseService
{
    public function list(array $filters, int $page, int $limit): array
    {
        $query = Bom::query();
        $query = $this->applyFilters($query, $filters);

        $total = $query->count();
        $offset = $page * $limit;

        $boms = $query->orderBy('ref', 'DESC')
            ->skip($offset)
            ->take($limit)
            ->get();

        return [
            'boms' => $boms,
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

    private function like(string $value): string
    {
        return '%' . addcslashes($value, '%_') . '%';
    }
}
