<?php

namespace Tests\Unit\Services;

use App\Models\ProductAttribute;
use App\Services\ProductAttributeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(ProductAttributeService::class)]
class ProductAttributeServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProductAttributeService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ProductAttributeService();
    }

    #[Test]
    public function it_gets_product_attributes_list(): void
    {
        // Arrange
        ProductAttribute::create([
            'ref' => 'COLOR',
            'label' => 'Color',
            'position' => 1,
            'entity' => 1
        ]);
        
        ProductAttribute::create([
            'ref' => 'SIZE',
            'label' => 'Size',
            'position' => 2,
            'entity' => 1
        ]);

        // Act
        $result = $this->service->getList([], 'position', 'ASC', 25, 0, 1);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('attributes', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertGreaterThanOrEqual(2, $result['total']);
    }

    #[Test]
    public function it_filters_by_reference(): void
    {
        // Arrange
        ProductAttribute::create(['ref' => 'COLOR', 'label' => 'Color', 'position' => 1, 'entity' => 1]);
        ProductAttribute::create(['ref' => 'SIZE', 'label' => 'Size', 'position' => 2, 'entity' => 1]);

        // Act
        $result = $this->service->getList(['ref' => 'COLOR'], 'position', 'ASC', 25, 0, 1);

        // Assert
        $this->assertEquals(1, $result['total']);
    }

    #[Test]
    public function it_paginates_results(): void
    {
        // Arrange
        for ($i = 1; $i <= 30; $i++) {
            ProductAttribute::create([
                'ref' => "ATTR$i",
                'label' => "Attribute $i",
                'position' => $i,
                'entity' => 1
            ]);
        }

        // Act
        $result = $this->service->getList([], 'position', 'ASC', 10, 0, 1);

        // Assert
        $this->assertEquals(30, $result['total']);
        $this->assertCount(10, $result['attributes']);
    }
}
