<?php

namespace Tests\Unit\Services;

use App\Models\ExpenseReport;
use App\Services\ExpenseReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(ExpenseReportService::class)]
class ExpenseReportServiceTest extends TestCase
{
    use RefreshDatabase;

    private ExpenseReportService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ExpenseReportService();
    }

    #[Test]
    public function it_lists_all_expense_reports(): void
    {
        ExpenseReport::create(['ref' => 'EXP001', 'date_create' => now(), 'entity' => 1]);
        ExpenseReport::create(['ref' => 'EXP002', 'date_create' => now(), 'entity' => 1]);

        $result = $this->service->list([], 0, 25);

        $this->assertCount(2, $result['reports']);
        $this->assertEquals(2, $result['total']);
    }

    #[Test]
    public function it_filters_reports_by_ref(): void
    {
        ExpenseReport::create(['ref' => 'EXP001', 'date_create' => now(), 'entity' => 1]);
        ExpenseReport::create(['ref' => 'EXP002', 'date_create' => now(), 'entity' => 1]);

        $result = $this->service->list(['all' => 'EXP001'], 0, 25);

        $this->assertCount(1, $result['reports']);
        $this->assertEquals('EXP001', $result['reports']->first()->ref);
    }
}
