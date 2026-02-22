<?php

namespace Tests\Unit;

use App\Models\Societe;
use App\Services\SocieteService;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(SocieteService::class)]
class SocieteServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    #[Test]
    public function it_applies_filters_and_pagination(): void
    {
        $builder = Mockery::mock();
        $builder->shouldReceive('where')->with(Mockery::type('Closure'))->andReturnSelf();
        $builder->shouldReceive('where')->with('nom', 'like', '%Acme%')->andReturnSelf();
        $builder->shouldReceive('where')->with('town', 'like', '%Paris%')->andReturnSelf();
        $builder->shouldReceive('where')->with('zip', 'like', '%75000%')->andReturnSelf();
        $builder->shouldReceive('orderBy')->with('nom', 'ASC')->andReturnSelf();
        $builder->shouldReceive('skip')->with(0)->andReturnSelf();
        $builder->shouldReceive('take')->with(25)->andReturnSelf();
        $builder->shouldReceive('get')->andReturn(collect([['nom' => 'Acme']]));

        Mockery::mock('alias:' . Societe::class)
            ->shouldReceive('query')
            ->andReturn($builder);

        $service = new SocieteService();

        $result = $service->list([
            'all' => 'Acme',
            'nom' => 'Acme',
            'town' => 'Paris',
            'zip' => '75000',
        ], 0, 25);

        $this->assertSame(1, $result['total']);
        $this->assertSame('Acme', $result['search']['nom']);
        $this->assertSame(25, $result['limit']);
    }
}
