<?php

namespace App\Services;

use App\Models\Holiday;
use Illuminate\Database\Eloquent\Builder;

class HolidayService
{
    public function list(array $filters, int $page, int $limit): array
    {
        $query = Holiday::query();
        $query = $this->applyFilters($query, $filters);

        $total = $query->count();
        $offset = $page * $limit;

        $holidays = $query->orderBy('date_create', 'DESC')
            ->skip($offset)
            ->take($limit)
            ->get();

        return [
            'holidays' => $holidays,
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
