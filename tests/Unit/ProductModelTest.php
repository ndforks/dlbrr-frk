<?php

namespace Tests\Unit;

use App\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_can_be_created(): void
    {
        $product = Product::create([
            'ref' => 'PROD001',
            'label' => 'Test Product',
            'entity' => 1,
            'fk_product_type' => 0,
            'price' => 100.00,
        ]);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('PROD001', $product->ref);
        $this->assertEquals('Test Product', $product->label);
        $this->assertEquals(100.00, $product->price);
    }

    public function test_product_has_commande_details_relationship(): void
    {
        $product = Product::create([
            'ref' => 'PROD001',
            'label' => 'Test Product',
            'entity' => 1,
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $product->commandeDetails());
    }

    public function test_product_has_facture_details_relationship(): void
    {
        $product = Product::create([
            'ref' => 'PROD001',
            'label' => 'Test Product',
            'entity' => 1,
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $product->factureDetails());
    }

    public function test_product_model_uses_correct_table(): void
    {
        $product = new Product();
        $this->assertEquals('llx_product', $product->getTable());
    }

    public function test_product_model_uses_correct_primary_key(): void
    {
        $product = new Product();
        $this->assertEquals('rowid', $product->getKeyName());
    }

    public function test_product_model_has_timestamps_disabled(): void
    {
        $product = new Product();
        $this->assertFalse($product->timestamps);
    }
}
