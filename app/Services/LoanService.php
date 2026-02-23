<?php

namespace App\Services;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Builder;

class LoanService extends BaseService
{
    public function list(array $filters, int $page, int $limit): array
    {
        $query = Loan::query();
        $query = $this->applyFilters($query, $filters);

        $total = $query->count();
        $offset = $page * $limit;

        $loans = $query->orderBy('datestart', 'DESC')
            ->skip($offset)
            ->take($limit)
            ->get();

        return [
            'loans' => $loans,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'search' => $filters,
        ];
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        if (!empty($filters['all'])) {
            $query->where('label', 'like', $this->like($filters['all']));
        }

        return $query;
    }
}
