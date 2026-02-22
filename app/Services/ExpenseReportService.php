<?php

namespace App\Services;

use App\Models\ExpenseReport;
use Illuminate\Database\Eloquent\Builder;

class ExpenseReportService
{
    public function list(array $filters, int $page, int $limit): array
    {
        $query = ExpenseReport::query();
        $query = $this->applyFilters($query, $filters);

        $total = $query->count();
        $offset = $page * $limit;

        $reports = $query->orderBy('date_create', 'DESC')
            ->skip($offset)
            ->take($limit)
            ->get();

        return [
            'reports' => $reports,
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
