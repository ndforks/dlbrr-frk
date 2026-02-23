<?php
/* Copyright (C) 2024 Laravel-Dolibarr Contributors <dev@laravel-dolibarr.org>
 * Copyright (C) 2024 GitHub Copilot AI Assistant
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

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
