<?php

namespace Tests\Feature;

use App\Http\Controllers\Projet\ListProjet;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Fakes\ProjetServiceFake;
use Tests\TestCase;

#[CoversClass(ListProjet::class)]
class ListProjetControllerTest extends TestCase
{
    #[Test]
    public function it_lists_projets_with_filters(): void
    {
        $service = new ProjetServiceFake();
        $service->setListResponse([
            'projets' => collect([['ref' => 'PRJ-001']]),
            'total' => 3,
            'page' => 0,
            'limit' => 25,
            'search' => [
                'all' => 'PRJ',
                'ref' => 'PRJ-001',
                'title' => 'New office',
            ],
        ]);

        $request = Request::create('/projet', 'GET', [
            'search_all' => 'PRJ',
            'search_ref' => 'PRJ-001',
            'search_title' => 'New office',
        ]);

        $controller = new ListProjet($service);
        $response = $controller($request);

        $this->assertSame('projet.list', $response->getName());
        $data = $response->getData();
        $this->assertSame(3, $data['total']);
        $this->assertSame('PRJ-001', $data['search']['ref']);
        $this->assertSame('New office', $data['search']['title']);
    }
}
