<?php

namespace Tests\Feature;

use App\Http\Controllers\Compta\Facture\ListFacture;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Fakes\FactureServiceFake;
use Tests\TestCase;

#[CoversClass(ListFacture::class)]
class ListFactureControllerTest extends TestCase
{
    #[Test]
    public function it_lists_factures_with_filters(): void
    {
        $service = new FactureServiceFake();
        $service->setListResponse([
            'factures' => collect([['ref' => 'FA001']]),
            'total' => 2,
            'page' => 0,
            'limit' => 25,
            'search' => [
                'all' => 'FA',
                'ref' => 'FA001',
                'societe' => 'Acme',
            ],
        ]);

        $request = Request::create('/compta/facture', 'GET', [
            'search_all' => 'FA',
            'search_ref' => 'FA001',
            'search_societe' => 'Acme',
        ]);

        $controller = new ListFacture($service);
        $response = $controller($request);

        $this->assertSame('facture.list', $response->getName());
        $data = $response->getData();
        $this->assertSame(2, $data['total']);
        $this->assertSame('FA001', $data['search']['ref']);
        $this->assertSame('Acme', $data['search']['societe']);
    }
}
