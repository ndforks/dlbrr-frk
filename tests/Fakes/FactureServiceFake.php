<?php

namespace Tests\Fakes;

use App\Services\FactureService;

class FactureServiceFake extends FactureService
{
    private array $listResponse = [];

    public function setListResponse(array $response): void
    {
        $this->listResponse = $response;
    }

    public function list(array $filters, int $page, int $limit): array
    {
        return $this->listResponse ?: [
            'factures' => collect([]),
            'total' => 0,
            'page' => $page,
            'limit' => $limit,
            'search' => $filters,
        ];
    }
}
