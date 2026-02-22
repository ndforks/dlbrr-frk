<?php

namespace Tests\Fakes;

use App\Services\SocieteService;
use Illuminate\Support\Collection;

class SocieteServiceFake extends SocieteService
{
    private array $listResponse = [];

    public function setListResponse(array $response): void
    {
        $this->listResponse = $response;
    }

    public function list(array $filters, int $page, int $limit): array
    {
        return $this->listResponse ?: [
            'societes' => collect([]),
            'total' => 0,
            'page' => $page,
            'limit' => $limit,
            'search' => $filters,
        ];
    }
}
