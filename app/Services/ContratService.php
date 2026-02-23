<?php

namespace App\Services;

use App\Models\Contrat;
use Illuminate\Database\Eloquent\Builder;

class ContratService extends BaseService
{
    public function list(array $filters, int $page, int $limit): array
    {
        $query = Contrat::query()->with('societe');
        $query = $this->applyFilters($query, $filters);

        $total = $query->count();
        $offset = $page * $limit;

        $contrats = $query->orderBy('ref', 'DESC')
            ->skip($offset)
            ->take($limit)
            ->get();

        return [
            'contrats' => $contrats,
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
