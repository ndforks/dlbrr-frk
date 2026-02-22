<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ContactService
{
    /**
     * List contacts with filters and pagination
     * 
     * @param array $filters Search filters
     * @param int $page Page number
     * @param int $limit Items per page
     * @return array{contacts: Collection<int, Contact>, total: int, page: int, limit: int, search: array}
     */
    public function list(array $filters, int $page, int $limit): array
    {
        $query = Contact::query()->with('societe');

        $query = $this->applyFilters($query, $filters);

        $total = $query->count();
        $offset = $page * $limit;

        $contacts = $query->orderBy('lastname', 'ASC')
            ->orderBy('firstname', 'ASC')
            ->skip($offset)
            ->take($limit)
            ->get();

        return [
            'contacts' => $contacts,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'search' => $filters,
        ];
    }

    /**
     * Apply search filters to query
     */
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        if (!empty($filters['all'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('lastname', 'like', $this->like($filters['all']))
                    ->orWhere('firstname', 'like', $this->like($filters['all']))
                    ->orWhere('email', 'like', $this->like($filters['all']))
                    ->orWhere('phone', 'like', $this->like($filters['all']))
                    ->orWhere('phone_mobile', 'like', $this->like($filters['all']));
            });
        }

        if (!empty($filters['lastname'])) {
            $query->where('lastname', 'like', $this->like($filters['lastname']));
        }

        if (!empty($filters['firstname'])) {
            $query->where('firstname', 'like', $this->like($filters['firstname']));
        }

        if (!empty($filters['email'])) {
            $query->where('email', 'like', $this->like($filters['email']));
        }

        if (!empty($filters['phone'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('phone', 'like', $this->like($filters['phone']))
                    ->orWhere('phone_mobile', 'like', $this->like($filters['phone']));
            });
        }

        if (!empty($filters['societe'])) {
            $query->whereHas('societe', function ($q) use ($filters) {
                $q->where('nom', 'like', $this->like($filters['societe']));
            });
        }

        return $query;
    }

    /**
     * Prepare value for LIKE query
     */
    private function like(string $value): string
    {
        $escaped = addcslashes($value, '%_');
        return '%' . $escaped . '%';
    }
}
