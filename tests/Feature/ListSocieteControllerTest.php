<?php

namespace Tests\Feature;

use App\Http\Controllers\Societe\ListSociete;
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
        $builder = Mockery::mock();
        $builder->shouldReceive('where')->with(Mockery::type('Closure'))->andReturnSelf();
        $builder->shouldReceive('where')->with('nom', 'like', '%Acme%')->andReturnSelf();
        $builder->shouldReceive('where')->with('town', 'like', '%Paris%')->andReturnSelf();
        $builder->shouldReceive('where')->with('zip', 'like', '%75000%')->andReturnSelf();
        $builder->shouldReceive('count')->andReturn(1);
        $builder->shouldReceive('orderBy')->with('nom', 'ASC')->andReturnSelf();
        $builder->shouldReceive('skip')->with(0)->andReturnSelf();
        $builder->shouldReceive('take')->with(25)->andReturnSelf();
        $builder->shouldReceive('get')->andReturn(collect([['nom' => 'Acme']]));

        Mockery::mock('alias:App\Models\Societe')
            ->shouldReceive('query')
            ->andReturn($builder);

        $request = Request::create('/societe', 'GET', [
            'search_all' => 'Acme',
            'search_nom' => 'Acme',
            'search_town' => 'Paris',
            'search_zip' => '75000',
        ]);

        $controller = new ListSociete();
        $response = $controller($request);

        $this->assertSame('societe.list', $response->getName());
        $data = $response->getData();
        $this->assertSame(1, $data['total']);
        $this->assertSame(25, $data['limit']);
        $this->assertSame('Acme', $data['search']['nom']);
    }
}
