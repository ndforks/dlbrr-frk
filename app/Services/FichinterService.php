<?php

namespace App\Services;

use App\Models\Fichinter;
use Illuminate\Database\Eloquent\Builder;

class FichinterService extends BaseService
{
    public function list(array $filters, int $page, int $limit): array
    {
        $query = Fichinter::query()->with('societe');
        $query = $this->applyFilters($query, $filters);

        $total = $query->count();
        $offset = $page * $limit;

        $fichinters = $query->orderBy('ref', 'DESC')
            ->skip($offset)
            ->take($limit)
            ->get();

        return [
            'fichinters' => $fichinters,
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
