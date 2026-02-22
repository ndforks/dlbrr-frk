<?php

namespace Tests\Feature;

use App\Http\Controllers\Projet\ListProjet;
use Illuminate\Http\Request;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(ListProjet::class)]
class ListProjetControllerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    #[Test]
    public function it_lists_projets_with_filters(): void
    {
        $builder = Mockery::mock();
        $builder->shouldReceive('where')->with(Mockery::type('Closure'))->andReturnSelf();
        $builder->shouldReceive('where')->with('ref', 'like', '%PRJ-001%')->andReturnSelf();
        $builder->shouldReceive('where')->with('title', 'like', '%New office%')->andReturnSelf();
        $builder->shouldReceive('count')->andReturn(3);
        $builder->shouldReceive('orderBy')->with('ref', 'DESC')->andReturnSelf();
        $builder->shouldReceive('skip')->with(0)->andReturnSelf();
        $builder->shouldReceive('take')->with(25)->andReturnSelf();
        $builder->shouldReceive('get')->andReturn(collect([['ref' => 'PRJ-001']]));

        Mockery::mock('alias:App\Models\Projet')
            ->shouldReceive('with')
            ->with('societe')
            ->andReturn($builder);

        $request = Request::create('/projet', 'GET', [
            'search_all' => 'PRJ',
            'search_ref' => 'PRJ-001',
            'search_title' => 'New office',
        ]);

        $controller = new ListProjet();
        $response = $controller($request);

        $this->assertSame('projet.list', $response->getName());
        $data = $response->getData();
        $this->assertSame(3, $data['total']);
        $this->assertSame('PRJ-001', $data['search']['ref']);
        $this->assertSame('New office', $data['search']['title']);
    }
}
