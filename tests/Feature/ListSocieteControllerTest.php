<?php

namespace Tests\Feature;

use App\Http\Controllers\Societe\ListSociete;
use App\Services\SocieteService;
use Illuminate\Http\Request;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(ListSociete::class)]
class ListSocieteControllerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    #[Test]
    public function it_lists_societes_with_filters(): void
    {
        $service = Mockery::mock(SocieteService::class);
        $service->shouldReceive('list')
            ->with([
                'all' => 'Acme',
                'nom' => 'Acme',
                'town' => 'Paris',
                'zip' => '75000',
            ], 0, 25)
            ->andReturn([
                'societes' => collect([['nom' => 'Acme']]),
                'total' => 1,
                'page' => 0,
                'limit' => 25,
                'search' => [
                    'all' => 'Acme',
                    'nom' => 'Acme',
                    'town' => 'Paris',
                    'zip' => '75000',
                ],
            ]);

        $request = Request::create('/societe', 'GET', [
            'search_all' => 'Acme',
            'search_nom' => 'Acme',
            'search_town' => 'Paris',
            'search_zip' => '75000',
        ]);

        $controller = new ListSociete($service);
        $response = $controller($request);

        $this->assertSame('societe.list', $response->getName());
        $data = $response->getData();
        $this->assertSame(1, $data['total']);
        $this->assertSame(25, $data['limit']);
        $this->assertSame('Acme', $data['search']['nom']);
    }
}
