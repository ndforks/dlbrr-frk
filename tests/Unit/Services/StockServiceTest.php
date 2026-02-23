<?php

namespace Tests\Unit\Services;

use App\Models\Entrepot;
use App\Models\Product;
use App\Models\StockMouvement;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(StockService::class)]
class StockServiceTest extends TestCase
{
    use RefreshDatabase;

    private StockService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new StockService();
    }

    #[Test]
    public function it_gets_stock_movements(): void
    {
        // Arrange
        $product = Product::create([
            'ref' => 'PROD001',
            'label' => 'Test Product',
            'entity' => 1
        ]);
        
        $warehouse = Entrepot::create([
            'ref' => 'WH001',
            'label' => 'Main Warehouse',
            'entity' => 1
        ]);
        
        StockMouvement::create([
            'fk_product' => $product->rowid,
            'fk_entrepot' => $warehouse->rowid,
            'value' => 10,
            'datem' => now(),
            'type' => 1
        ]);

        // Act
        $result = $this->service->getMovements([], 'datem', 'DESC', 25, 0, 1);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('movements', $result);
        $this->assertArrayHasKey('total', $result);
    }

    #[Test]
    public function it_filters_movements_by_product_ref(): void
    {
        // Arrange
        $product1 = Product::create(['ref' => 'PROD001', 'label' => 'Product 1', 'entity' => 1]);
        $product2 = Product::create(['ref' => 'PROD002', 'label' => 'Product 2', 'entity' => 1]);
        $warehouse = Entrepot::create(['ref' => 'WH001', 'label' => 'Warehouse', 'entity' => 1]);
        
        StockMouvement::create([
            'fk_product' => $product1->rowid,
            'fk_entrepot' => $warehouse->rowid,
            'value' => 10,
            'datem' => now(),
            'type' => 1
        ]);
        
        StockMouvement::create([
            'fk_product' => $product2->rowid,
            'fk_entrepot' => $warehouse->rowid,
            'value' => 5,
            'datem' => now(),
            'type' => 1
        ]);

        // Act
        $result = $this->service->getMovements(['product_ref' => 'PROD001'], 'datem', 'DESC', 25, 0, 1);

        // Assert
        $this->assertEquals(1, $result['total']);
    }

    #[Test]
    public function it_gets_warehouses(): void
    {
        // Arrange
        Entrepot::create(['ref' => 'WH001', 'label' => 'Warehouse 1', 'entity' => 1]);
        Entrepot::create(['ref' => 'WH002', 'label' => 'Warehouse 2', 'entity' => 1]);

        // Act
        $result = $this->service->getWarehouses(1);

        // Assert
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
        $this->assertCount(2, $result);
    }
}
