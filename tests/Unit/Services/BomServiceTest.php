<?php

namespace Tests\Unit\Services;

use App\Models\Bom;
use App\Services\BomService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(BomService::class)]
class BomServiceTest extends TestCase
{
    use RefreshDatabase;

    private BomService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new BomService();
    }

    #[Test]
    public function it_lists_all_boms(): void
    {
        Bom::create(['ref' => 'BOM001', 'label' => 'BOM 1', 'entity' => 1]);
        Bom::create(['ref' => 'BOM002', 'label' => 'BOM 2', 'entity' => 1]);

        $result = $this->service->list([], 0, 25);

        $this->assertCount(2, $result['boms']);
        $this->assertEquals(2, $result['total']);
    }

    #[Test]
    public function it_filters_boms_by_ref(): void
    {
        Bom::create(['ref' => 'BOM001', 'label' => 'BOM 1', 'entity' => 1]);
        Bom::create(['ref' => 'BOM002', 'label' => 'BOM 2', 'entity' => 1]);

        $result = $this->service->list(['all' => 'BOM001'], 0, 25);

        $this->assertCount(1, $result['boms']);
        $this->assertEquals('BOM001', $result['boms']->first()->ref);
    }
}
