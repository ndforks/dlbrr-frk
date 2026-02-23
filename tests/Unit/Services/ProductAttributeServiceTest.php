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
        ProductAttribute::factory()->create([
            'ref' => 'COLOR',
            'label' => 'Color',
            'position' => 1,
        ]);
        
        ProductAttribute::factory()->create([
            'ref' => 'SIZE',
            'label' => 'Size',
            'position' => 2,
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
        ProductAttribute::factory()->create(['ref' => 'COLOR', 'label' => 'Color', 'position' => 1]);
        ProductAttribute::factory()->create(['ref' => 'SIZE', 'label' => 'Size', 'position' => 2]);

        // Act
        $result = $this->service->getList(['ref' => 'COLOR'], 'position', 'ASC', 25, 0, 1);

        // Assert
        $this->assertEquals(1, $result['total']);
    }

    #[Test]
    public function it_filters_by_label(): void
    {
        // Arrange
        ProductAttribute::factory()->create(['ref' => 'COLOR', 'label' => 'Product Color']);
        ProductAttribute::factory()->create(['ref' => 'SIZE', 'label' => 'Product Size']);

        // Act
        $result = $this->service->getList(['label' => 'Color'], 'position', 'ASC', 25, 0, 1);

        // Assert
        $this->assertEquals(1, $result['total']);
    }

    #[Test]
    public function it_paginates_results(): void
    {
        // Arrange
        ProductAttribute::factory()->count(30)->create();

        // Act
        $result = $this->service->getList([], 'position', 'ASC', 10, 0, 1);

        // Assert
        $this->assertEquals(30, $result['total']);
        $this->assertCount(10, $result['attributes']);
    }

    #[Test]
    public function it_gets_attribute_by_id(): void
    {
        // Arrange
        $attribute = ProductAttribute::factory()->create(['ref' => 'TEST']);

        // Act
        $result = $this->service->getById($attribute->rowid);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($attribute->rowid, $result->rowid);
        $this->assertEquals('TEST', $result->ref);
    }

    #[Test]
    public function it_returns_null_for_nonexistent_id(): void
    {
        // Act
        $result = $this->service->getById(99999);

        // Assert
        $this->assertNull($result);
    }

    #[Test]
    public function it_creates_a_product_attribute(): void
    {
        // Arrange
        $data = [
            'ref' => 'NEWATTR',
            'label' => 'New Attribute',
            'position' => 10,
            'entity' => 1,
        ];

        // Act
        $result = $this->service->create($data);

        // Assert
        $this->assertInstanceOf(ProductAttribute::class, $result);
        $this->assertEquals('NEWATTR', $result->ref);
        $this->assertDatabaseHas('llx_product_attribute', ['ref' => 'NEWATTR']);
    }

    #[Test]
    public function it_updates_a_product_attribute(): void
    {
        // Arrange
        $attribute = ProductAttribute::factory()->create(['label' => 'Old Label']);

        // Act
        $result = $this->service->update($attribute->rowid, ['label' => 'New Label']);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('llx_product_attribute', [
            'rowid' => $attribute->rowid,
            'label' => 'New Label',
        ]);
    }

    #[Test]
    public function it_deletes_a_product_attribute(): void
    {
        // Arrange
        $attribute = ProductAttribute::factory()->create();
        $attributeId = $attribute->rowid;

        // Act
        $result = $this->service->delete($attributeId);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('llx_product_attribute', ['rowid' => $attributeId]);
    }
}
