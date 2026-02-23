<?php

namespace Tests\Unit\Services;

use App\Models\Asset;
use App\Services\AssetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(AssetService::class)]
class AssetServiceTest extends TestCase
{
    use RefreshDatabase;

    private AssetService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AssetService();
    }

    #[Test]
    public function it_lists_all_assets(): void
    {
        Asset::create(['ref' => 'AST001', 'label' => 'Asset 1', 'entity' => 1]);
        Asset::create(['ref' => 'AST002', 'label' => 'Asset 2', 'entity' => 1]);

        $result = $this->service->list([], 0, 25);

        $this->assertCount(2, $result['assets']);
        $this->assertEquals(2, $result['total']);
    }

    #[Test]
    public function it_filters_assets_by_ref(): void
    {
        Asset::create(['ref' => 'AST001', 'label' => 'Asset 1', 'entity' => 1]);
        Asset::create(['ref' => 'AST002', 'label' => 'Asset 2', 'entity' => 1]);

        $result = $this->service->list(['all' => 'AST001'], 0, 25);

        $this->assertCount(1, $result['assets']);
        $this->assertEquals('AST001', $result['assets']->first()->ref);
    }
}
