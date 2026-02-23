<?php

namespace App\Services;

use App\Models\Adherent;
use Illuminate\Database\Eloquent\Builder;

class AdherentService extends BaseService
{
    public function list(array $filters, int $page, int $limit): array
    {
        $query = Adherent::query();
        $query = $this->applyFilters($query, $filters);

        $total = $query->count();
        $offset = $page * $limit;

        $adherents = $query->orderBy('lastname', 'ASC')
            ->skip($offset)
            ->take($limit)
            ->get();

        return [
            'adherents' => $adherents,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'search' => $filters,
        ];
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        if (!empty($filters['all'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('firstname', 'like', $this->like($filters['all']))
                    ->orWhere('lastname', 'like', $this->like($filters['all']));
            });
        }

        return $query;
    }
}
