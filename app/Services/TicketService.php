<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;

class TicketService
{
    public function list(array $filters, int $page, int $limit): array
    {
        $query = Ticket::query()->with('societe');
        $query = $this->applyFilters($query, $filters);

        $total = $query->count();
        $offset = $page * $limit;

        $tickets = $query->orderBy('datec', 'DESC')
            ->skip($offset)
            ->take($limit)
            ->get();

        return [
            'tickets' => $tickets,
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
                    ->orWhere('subject', 'like', $this->like($filters['all']));
            });
        }

        if (!empty($filters['ref'])) {
            $query->where('ref', 'like', $this->like($filters['ref']));
        }

        return $query;
    }

    private function like(string $value): string
    {
        return '%' . addcslashes($value, '%_') . '%';
    }
}
