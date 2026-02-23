<?php

namespace Tests\Unit\Services;

use App\Models\HrmPosition;
use App\Services\HrmService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(HrmService::class)]
class HrmServiceTest extends TestCase
{
    use RefreshDatabase;

    private HrmService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new HrmService();
    }

    #[Test]
    public function it_gets_positions_list(): void
    {
        // Arrange
        HrmPosition::factory()->create([
            'ref' => 'POS001',
            'label' => 'Software Developer',
        ]);
        
        HrmPosition::factory()->create([
            'ref' => 'POS002',
            'label' => 'Project Manager',
        ]);

        // Act
        $result = $this->service->getPositions([], 'label', 'ASC', 25, 0, 1);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('positions', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertEquals(2, $result['total']);
    }

    #[Test]
    public function it_filters_positions_by_label(): void
    {
        // Arrange
        HrmPosition::factory()->create(['ref' => 'POS001', 'label' => 'Software Developer']);
        HrmPosition::factory()->create(['ref' => 'POS002', 'label' => 'Project Manager']);

        // Act
        $result = $this->service->getPositions(['label' => 'Developer'], 'label', 'ASC', 25, 0, 1);

        // Assert
        $this->assertEquals(1, $result['total']);
    }

    #[Test]
    public function it_paginates_positions(): void
    {
        // Arrange
        HrmPosition::factory()->count(30)->create();

        // Act
        $result = $this->service->getPositions([], 'label', 'ASC', 10, 0, 1);

        // Assert
        $this->assertEquals(30, $result['total']);
        $this->assertCount(10, $result['positions']);
    }
}
