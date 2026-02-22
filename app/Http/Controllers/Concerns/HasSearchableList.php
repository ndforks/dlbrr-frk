<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Trait HasSearchableList
 * 
 * Provides reusable search and pagination logic following DRY principles.
 * Eliminates duplicate code across 18+ List controllers.
 */
trait HasSearchableList
{
    /**
     * Default pagination limit
     */
    protected int $defaultLimit = 25;

    /**
     * Apply pagination to query
     */
    protected function applyPagination(Builder $query, int $page, int $limit): Builder
    {
        $offset = $page * $limit;
        return $query->skip($offset)->take($limit);
    }

    /**
     * Get pagination parameters from request
     */
    protected function getPaginationParams(Request $request): array
    {
        return [
            'page' => $request->integer('page', 0),
            'limit' => $request->integer('limit', $this->defaultLimit),
        ];
    }

    /**
     * Apply search filters to query
     * 
     * This method can be overridden in controllers to apply specific filters
     */
    protected function applySearchFilters(Builder $query, Request $request): Builder
    {
        return $query;
    }

    /**
     * Get search parameters from request
     * 
     * This method can be overridden in controllers to define specific search fields
     */
    protected function getSearchParams(Request $request): array
    {
        return [];
    }

    /**
     * Apply a "search all" filter that searches across multiple fields
     */
    protected function applySearchAll(Builder $query, string $searchTerm, array $fields): Builder
    {
        if (empty($searchTerm)) {
            return $query;
        }

        return $query->where(function($q) use ($searchTerm, $fields) {
            foreach ($fields as $field) {
                $q->orWhere($field, 'like', "%{$searchTerm}%");
            }
        });
    }

    /**
     * Apply a simple field search
     */
    protected function applyFieldSearch(Builder $query, ?string $searchTerm, string $field): Builder
    {
        if (empty($searchTerm)) {
            return $query;
        }

        return $query->where($field, 'like', "%{$searchTerm}%");
    }

    /**
     * Apply a relationship search
     */
    protected function applyRelationshipSearch(Builder $query, ?string $searchTerm, string $relation, string $field): Builder
    {
        if (empty($searchTerm)) {
            return $query;
        }

        return $query->whereHas($relation, function($q) use ($searchTerm, $field) {
            $q->where($field, 'like', "%{$searchTerm}%");
        });
    }

    /**
     * Build paginated view data
     */
    protected function buildListViewData(Builder $query, array $pagination, array $searchParams = []): array
    {
        $total = $query->count();
        
        $items = $this->applyPagination($query, $pagination['page'], $pagination['limit'])->get();
        
        return [
            'items' => $items,
            'total' => $total,
            'page' => $pagination['page'],
            'limit' => $pagination['limit'],
            'search' => $searchParams,
        ];
    }
}
