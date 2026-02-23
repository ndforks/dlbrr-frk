<?php

namespace App\Services;

use App\Models\Societe;
use Illuminate\Support\Collection;

class SocieteService extends BaseService
{
    /**
     * @return array{societes: Collection<int, Societe>, total: int, page: int, limit: int, search: array}
     */
    public function list(array $filters, int $page, int $limit): array
    {
        $query = Societe::query();

        if (!empty($filters['all'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('nom', 'like', $this->like($filters['all']))
                    ->orWhere('name_alias', 'like', $this->like($filters['all']))
                    ->orWhere('email', 'like', $this->like($filters['all']))
                    ->orWhere('code_client', 'like', $this->like($filters['all']));
            });
        }

        if (!empty($filters['nom'])) {
            $query->where('nom', 'like', $this->like($filters['nom']));
        }

        if (!empty($filters['town'])) {
            $query->where('town', 'like', $this->like($filters['town']));
        }

        if (!empty($filters['zip'])) {
            $query->where('zip', 'like', $this->like($filters['zip']));
        }

        $offset = $page * $limit;

        $societes = $query->orderBy('nom', 'ASC')
            ->skip($offset)
            ->take($limit)
            ->get();

        return [
            'societes' => $societes,
            'total' => $societes->count(),
            'page' => $page,
            'limit' => $limit,
            'search' => $filters,
        ];
    }
}
