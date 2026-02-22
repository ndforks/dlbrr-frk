<?php

namespace Tests\Unit\Services;

use App\Models\Loan;
use App\Services\LoanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(LoanService::class)]
class LoanServiceTest extends TestCase
{
    use RefreshDatabase;

    private LoanService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new LoanService();
    }

    #[Test]
    public function it_lists_all_loans(): void
    {
        Loan::create(['label' => 'Loan 1', 'capital' => 10000, 'datestart' => now(), 'entity' => 1]);
        Loan::create(['label' => 'Loan 2', 'capital' => 20000, 'datestart' => now(), 'entity' => 1]);

        $result = $this->service->list([], 0, 25);

        $this->assertCount(2, $result['loans']);
        $this->assertEquals(2, $result['total']);
    }

    #[Test]
    public function it_filters_loans_by_label(): void
    {
        Loan::create(['label' => 'Loan 1', 'capital' => 10000, 'datestart' => now(), 'entity' => 1]);
        Loan::create(['label' => 'Loan 2', 'capital' => 20000, 'datestart' => now(), 'entity' => 1]);

        $result = $this->service->list(['all' => 'Loan 1'], 0, 25);

        $this->assertCount(1, $result['loans']);
        $this->assertEquals('Loan 1', $result['loans']->first()->label);
    }
}
