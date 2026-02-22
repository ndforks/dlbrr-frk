<?php

namespace Tests\Feature;

use App\Http\Controllers\Compta\Facture\ListFacture;
use Illuminate\Http\Request;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(ListFacture::class)]
class ListFactureControllerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    #[Test]
    public function it_lists_factures_with_filters(): void
    {
        $builder = Mockery::mock();
        $builder->shouldReceive('where')->with(Mockery::type('Closure'))->andReturnSelf();
        $builder->shouldReceive('where')->with('ref', 'like', '%FA001%')->andReturnSelf();
        $builder->shouldReceive('whereHas')->with('societe', Mockery::type('Closure'))->andReturnSelf();
        $builder->shouldReceive('count')->andReturn(2);
        $builder->shouldReceive('orderBy')->with('datef', 'DESC')->andReturnSelf();
        $builder->shouldReceive('skip')->with(0)->andReturnSelf();
        $builder->shouldReceive('take')->with(25)->andReturnSelf();
        $builder->shouldReceive('get')->andReturn(collect([['ref' => 'FA001']]));

        Mockery::mock('alias:App\Models\Facture')
            ->shouldReceive('with')
            ->with('societe')
            ->andReturn($builder);

        $request = Request::create('/compta/facture', 'GET', [
            'search_all' => 'FA',
            'search_ref' => 'FA001',
            'search_societe' => 'Acme',
        ]);

        $controller = new ListFacture();
        $response = $controller($request);

        $this->assertSame('facture.list', $response->getName());
        $data = $response->getData();
        $this->assertSame(2, $data['total']);
        $this->assertSame('FA001', $data['search']['ref']);
        $this->assertSame('Acme', $data['search']['societe']);
    }
}
