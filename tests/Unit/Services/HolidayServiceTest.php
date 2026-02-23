<?php

namespace Tests\Unit\Services;

use App\Models\Holiday;
use App\Services\HolidayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(HolidayService::class)]
class HolidayServiceTest extends TestCase
{
    use RefreshDatabase;

    private HolidayService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new HolidayService();
    }

    #[Test]
    public function it_lists_all_holidays(): void
    {
        Holiday::create(['fk_user' => 1, 'date_debut' => now(), 'date_fin' => now()->addDays(5), 'entity' => 1]);
        Holiday::create(['fk_user' => 1, 'date_debut' => now(), 'date_fin' => now()->addDays(3), 'entity' => 1]);

        $result = $this->service->list([], 0, 25);

        $this->assertCount(2, $result['holidays']);
        $this->assertEquals(2, $result['total']);
    }
}
