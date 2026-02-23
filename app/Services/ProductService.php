<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductService extends BaseService
{
    public function list(array $filters, int $page, int $limit): array
    {
        $query = Product::query();
        $query = $this->applyFilters($query, $filters);

        $total = $query->count();
        $offset = $page * $limit;

        $products = $query->orderBy('ref', 'ASC')
            ->skip($offset)
            ->take($limit)
            ->get();

        return [
            'products' => $products,
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
                $q->where('ref', 'like', $this->like($filters['all']))
                    ->orWhere('label', 'like', $this->like($filters['all']))
                    ->orWhere('description', 'like', $this->like($filters['all']));
            });
        }

        if (!empty($filters['ref'])) {
            $query->where('ref', 'like', $this->like($filters['ref']));
        }

        if (!empty($filters['label'])) {
            $query->where('label', 'like', $this->like($filters['label']));
        }

        return $query;
    }
}
