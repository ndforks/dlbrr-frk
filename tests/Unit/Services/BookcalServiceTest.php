<?php

namespace Tests\Unit\Services;

use App\Models\Bookcal;
use App\Services\BookcalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(BookcalService::class)]
class BookcalServiceTest extends TestCase
{
    use RefreshDatabase;

    private BookcalService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new BookcalService();
    }

    #[Test]
    public function it_gets_calendars_list(): void
    {
        // Arrange
        Bookcal::create([
            'ref' => 'CAL001',
            'label' => 'Meeting Calendar',
            'entity' => 1
        ]);
        
        Bookcal::create([
            'ref' => 'CAL002',
            'label' => 'Event Calendar',
            'entity' => 1
        ]);

        // Act
        $result = $this->service->getCalendars([], 'label', 'ASC', 25, 0, 1);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('calendars', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertEquals(2, $result['total']);
    }

    #[Test]
    public function it_filters_calendars_by_label(): void
    {
        // Arrange
        Bookcal::create(['ref' => 'CAL001', 'label' => 'Meeting Calendar', 'entity' => 1]);
        Bookcal::create(['ref' => 'CAL002', 'label' => 'Event Calendar', 'entity' => 1]);

        // Act
        $result = $this->service->getCalendars(['label' => 'Meeting'], 'label', 'ASC', 25, 0, 1);

        // Assert
        $this->assertEquals(1, $result['total']);
    }

    #[Test]
    public function it_paginates_calendars(): void
    {
        // Arrange
        for ($i = 1; $i <= 30; $i++) {
            Bookcal::create(['ref' => "CAL$i", 'label' => "Calendar $i", 'entity' => 1]);
        }

        // Act
        $result = $this->service->getCalendars([], 'label', 'ASC', 10, 0, 1);

        // Assert
        $this->assertEquals(30, $result['total']);
        $this->assertCount(10, $result['calendars']);
    }
}
