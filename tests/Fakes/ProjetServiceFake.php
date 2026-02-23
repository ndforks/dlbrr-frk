<?php

namespace Tests\Fakes;

use App\Services\ProjetService;

class ProjetServiceFake extends ProjetService
{
    private array $listResponse = [];

    public function setListResponse(array $response): void
    {
        $this->listResponse = $response;
    }

    public function list(array $filters, int $page, int $limit): array
    {
        return $this->listResponse ?: [
            'projets' => collect([]),
            'total' => 0,
            'page' => $page,
            'limit' => $limit,
            'search' => $filters,
        ];
    }
}
