<?php

namespace App\Services;

use App\Models\Expedition;
use Illuminate\Database\Eloquent\Builder;

class ExpeditionService extends BaseService
{
    public function list(array $filters, int $page, int $limit): array
    {
        $query = Expedition::query()->with('societe');

        $query = $this->applyFilters($query, $filters);

        $total = $query->count();
        $offset = $page * $limit;

        $expeditions = $query->orderBy('date_expedition', 'DESC')
            ->skip($offset)
            ->take($limit)
            ->get();

        return [
            'expeditions' => $expeditions,
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

        if (!empty($filters['ref'])) {
            $query->where('ref', 'like', $this->like($filters['ref']));
        }

        return $query;
    }

    private function like(string $value): string
    {
        $escaped = addcslashes($value, '%_');
        return '%' . $escaped . '%';
    }
}
