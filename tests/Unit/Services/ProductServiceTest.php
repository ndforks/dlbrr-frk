<?php

namespace Tests\Unit\Services;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(ProductService::class)]
class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProductService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ProductService();
    }

    #[Test]
    public function it_lists_all_products(): void
    {
        Product::create(['ref' => 'PROD001', 'label' => 'Product 1', 'entity' => 1]);
        Product::create(['ref' => 'PROD002', 'label' => 'Product 2', 'entity' => 1]);

        $result = $this->service->list([], 0, 25);

        $this->assertCount(2, $result['products']);
        $this->assertEquals(2, $result['total']);
    }

    #[Test]
    public function it_filters_products_by_ref(): void
    {
        Product::create(['ref' => 'PROD001', 'label' => 'Product 1', 'entity' => 1]);
        Product::create(['ref' => 'PROD002', 'label' => 'Product 2', 'entity' => 1]);

        $result = $this->service->list(['ref' => 'PROD001'], 0, 25);

        $this->assertCount(1, $result['products']);
        $this->assertEquals('PROD001', $result['products']->first()->ref);
    }
}
