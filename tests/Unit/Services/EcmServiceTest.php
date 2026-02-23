<?php

namespace Tests\Unit\Services;

use App\Models\EcmDirectory;
use App\Services\EcmService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(EcmService::class)]
class EcmServiceTest extends TestCase
{
    use RefreshDatabase;

    private EcmService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new EcmService();
    }

    #[Test]
    public function it_gets_directories_list(): void
    {
        // Arrange
        EcmDirectory::create([
            'label' => 'Documents',
            'description' => 'Main documents folder',
            'entity' => 1
        ]);
        
        EcmDirectory::create([
            'label' => 'Images',
            'description' => 'Image files',
            'entity' => 1
        ]);

        // Act
        $result = $this->service->getDirectories([], 'label', 'ASC', 25, 0, 1);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('directories', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertEquals(2, $result['total']);
    }

    #[Test]
    public function it_filters_directories_by_label(): void
    {
        // Arrange
        EcmDirectory::create(['label' => 'Documents', 'entity' => 1]);
        EcmDirectory::create(['label' => 'Images', 'entity' => 1]);

        // Act
        $result = $this->service->getDirectories(['label' => 'Documents'], 'label', 'ASC', 25, 0, 1);

        // Assert
        $this->assertEquals(1, $result['total']);
    }

    #[Test]
    public function it_paginates_directories(): void
    {
        // Arrange
        for ($i = 1; $i <= 30; $i++) {
            EcmDirectory::create(['label' => "Directory $i", 'entity' => 1]);
        }

        // Act
        $result = $this->service->getDirectories([], 'label', 'ASC', 10, 0, 1);

        // Assert
        $this->assertEquals(30, $result['total']);
        $this->assertCount(10, $result['directories']);
    }
}
