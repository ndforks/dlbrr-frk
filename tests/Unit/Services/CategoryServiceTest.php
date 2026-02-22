<?php

namespace Tests\Unit\Services;

use App\Models\Categorie;
use App\Services\CategoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    private CategoryService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CategoryService();
    }

    #[Test]
    public function it_retrieves_paginated_categories(): void
    {
        // Arrange
        Categorie::create([
            'ref' => 'CAT001',
            'label' => 'Category 1',
            'type' => 0,
            'entity' => 1,
        ]);

        Categorie::create([
            'ref' => 'CAT002',
            'label' => 'Category 2',
            'type' => 0,
            'entity' => 1,
        ]);

        // Act
        $result = $this->service->getList([], 'label', 'ASC', 10, 0, null, 1);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('categories', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertEquals(2, $result['total']);
    }

    #[Test]
    public function it_filters_categories_by_type(): void
    {
        // Arrange
        Categorie::create([
            'ref' => 'PROD001',
            'label' => 'Product Category',
            'type' => 0, // Product type
            'entity' => 1,
        ]);

        Categorie::create([
            'ref' => 'CUST001',
            'label' => 'Customer Category',
            'type' => 2, // Customer type
            'entity' => 1,
        ]);

        // Act
        $result = $this->service->getList([], 'label', 'ASC', 10, 0, 0, 1);

        // Assert
        $this->assertEquals(1, $result['total']);
        $this->assertEquals('Product Category', $result['categories'][0]->label);
    }

    #[Test]
    public function it_retrieves_category_by_id(): void
    {
        // Arrange
        $category = Categorie::create([
            'ref' => 'CAT001',
            'label' => 'Test Category',
            'type' => 0,
            'entity' => 1,
        ]);

        // Act
        $retrieved = $this->service->getById($category->rowid);

        // Assert
        $this->assertNotNull($retrieved);
        $this->assertEquals('Test Category', $retrieved->label);
    }

    #[Test]
    public function it_creates_new_category(): void
    {
        // Arrange
        $data = [
            'ref' => 'NEW001',
            'label' => 'New Category',
            'type' => 0,
            'entity' => 1,
        ];

        // Act
        $category = $this->service->create($data);

        // Assert
        $this->assertInstanceOf(Categorie::class, $category);
        $this->assertEquals('New Category', $category->label);
        $this->assertDatabaseHas('llx_categorie', ['ref' => 'NEW001']);
    }

    #[Test]
    public function it_updates_existing_category(): void
    {
        // Arrange
        $category = Categorie::create([
            'ref' => 'CAT001',
            'label' => 'Original Label',
            'type' => 0,
            'entity' => 1,
        ]);

        // Act
        $result = $this->service->update($category->rowid, [
            'label' => 'Updated Label',
        ]);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('llx_categorie', [
            'ref' => 'CAT001',
            'label' => 'Updated Label',
        ]);
    }

    #[Test]
    public function it_deletes_category_without_children(): void
    {
        // Arrange
        $category = Categorie::create([
            'ref' => 'DEL001',
            'label' => 'To Delete',
            'type' => 0,
            'entity' => 1,
        ]);

        $categoryId = $category->rowid;

        // Act
        $result = $this->service->delete($categoryId);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('llx_categorie', ['rowid' => $categoryId]);
    }

    #[Test]
    public function it_prevents_deleting_category_with_children(): void
    {
        // Arrange
        $parent = Categorie::create([
            'ref' => 'PARENT',
            'label' => 'Parent Category',
            'type' => 0,
            'entity' => 1,
        ]);

        Categorie::create([
            'ref' => 'CHILD',
            'label' => 'Child Category',
            'type' => 0,
            'fk_parent' => $parent->rowid,
            'entity' => 1,
        ]);

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cannot delete category with children');
        $this->service->delete($parent->rowid);
    }

    #[Test]
    public function it_retrieves_categories_by_type(): void
    {
        // Arrange
        Categorie::create([
            'ref' => 'PROD001',
            'label' => 'Product 1',
            'type' => 0,
            'entity' => 1,
        ]);

        Categorie::create([
            'ref' => 'PROD002',
            'label' => 'Product 2',
            'type' => 0,
            'entity' => 1,
        ]);

        Categorie::create([
            'ref' => 'CUST001',
            'label' => 'Customer 1',
            'type' => 2,
            'entity' => 1,
        ]);

        // Act
        $categories = $this->service->getByType(0, 1);

        // Assert
        $this->assertCount(2, $categories);
        $this->assertTrue($categories->every(fn($cat) => $cat->type === 0));
    }

    #[Test]
    public function it_builds_category_tree(): void
    {
        // Arrange
        $parent = Categorie::create([
            'ref' => 'PARENT',
            'label' => 'Parent',
            'type' => 0,
            'entity' => 1,
        ]);

        Categorie::create([
            'ref' => 'CHILD1',
            'label' => 'Child 1',
            'type' => 0,
            'fk_parent' => $parent->rowid,
            'entity' => 1,
        ]);

        Categorie::create([
            'ref' => 'CHILD2',
            'label' => 'Child 2',
            'type' => 0,
            'fk_parent' => $parent->rowid,
            'entity' => 1,
        ]);

        // Act
        $tree = $this->service->getTree(0, 1);

        // Assert
        $this->assertIsArray($tree);
        $this->assertCount(1, $tree);
        $this->assertEquals('Parent', $tree[0]->label);
        $this->assertCount(2, $tree[0]->children);
    }
}
