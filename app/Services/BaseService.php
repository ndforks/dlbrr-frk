<?php

namespace App\Services;

/**
 * Base Service Class
 * 
 * Provides common functionality for all service classes
 * following DRY (Don't Repeat Yourself) principle
 */
abstract class BaseService
{
    /**
     * Format a value for SQL LIKE query
     * 
     * @param string $value The search value
     * @return string The formatted value with wildcards and escaped special characters
     */
    protected function like(string $value): string
    {
        $escaped = addcslashes($value, '%_');
        return '%' . $escaped . '%';
    }
    
    /**
     * Get pagination offset
     * 
     * @param int $page Page number (0-indexed)
     * @param int $limit Items per page
     * @return int The offset value
     */
    protected function getOffset(int $page, int $limit): int
    {
        return $page * $limit;
    }
    
    /**
     * Prepare list response with pagination metadata
     * 
     * @param mixed $data The data collection
     * @param int $total Total count
     * @param int $page Current page
     * @param int $limit Items per page
     * @param array $filters Applied filters
     * @return array Response array with data and metadata
     */
    protected function prepareListResponse($data, int $total, int $page, int $limit, array $filters = []): array
    {
        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'filters' => $filters,
        ];
    }
}
