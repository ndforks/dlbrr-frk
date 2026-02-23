<?php

namespace Tests\Unit\Services;

use App\Models\Mrp;
use App\Services\MrpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(MrpService::class)]
class MrpServiceTest extends TestCase
{
    use RefreshDatabase;

    private MrpService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new MrpService();
    }

    #[Test]
    public function it_lists_all_mrps(): void
    {
        Mrp::create(['ref' => 'MRP001', 'label' => 'MRP 1', 'entity' => 1]);
        Mrp::create(['ref' => 'MRP002', 'label' => 'MRP 2', 'entity' => 1]);

        $result = $this->service->list([], 0, 25);

        $this->assertCount(2, $result['mrps']);
        $this->assertEquals(2, $result['total']);
    }

    #[Test]
    public function it_filters_mrps_by_ref(): void
    {
        Mrp::create(['ref' => 'MRP001', 'label' => 'MRP 1', 'entity' => 1]);
        Mrp::create(['ref' => 'MRP002', 'label' => 'MRP 2', 'entity' => 1]);

        $result = $this->service->list(['all' => 'MRP001'], 0, 25);

        $this->assertCount(1, $result['mrps']);
        $this->assertEquals('MRP001', $result['mrps']->first()->ref);
    }
}
