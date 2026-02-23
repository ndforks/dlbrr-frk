<?php

namespace App\Services;

use App\Models\Propal;
use Illuminate\Database\Eloquent\Builder;

class PropalService extends BaseService
{
    public function list(array $filters, int $page, int $limit): array
    {
        $query = Propal::query()->with('societe');
        $query = $this->applyFilters($query, $filters);

        $total = $query->count();
        $offset = $page * $limit;

        $propals = $query->orderBy('datep', 'DESC')
            ->skip($offset)
            ->take($limit)
            ->get();

        return [
            'propals' => $propals,
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
        return '%' . addcslashes($value, '%_') . '%';
    }
}
