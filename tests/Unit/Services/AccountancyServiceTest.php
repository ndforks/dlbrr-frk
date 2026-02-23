<?php

namespace Tests\Unit\Services;

use App\Services\AccountancyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(AccountancyService::class)]
class AccountancyServiceTest extends TestCase
{
    use RefreshDatabase;

    private AccountancyService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AccountancyService();
    }

    #[Test]
    public function it_gets_accounting_entries(): void
    {
        // Arrange
        DB::table('llx_accounting_bookkeeping')->insert([
            'doc_ref' => 'FA001',
            'numero_compte' => '401000',
            'code_journal' => 'VT',
            'doc_date' => now(),
            'debit' => 100.00,
            'credit' => 0.00,
            'entity' => 1
        ]);

        // Act
        $result = $this->service->getEntries([], 'doc_date', 'DESC', 25, 0, 1);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('entries', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertGreaterThanOrEqual(1, $result['total']);
    }

    #[Test]
    public function it_filters_by_doc_ref(): void
    {
        // Arrange
        DB::table('llx_accounting_bookkeeping')->insert([
            ['doc_ref' => 'FA001', 'numero_compte' => '401000', 'code_journal' => 'VT', 'doc_date' => now(), 'debit' => 100, 'credit' => 0, 'entity' => 1],
            ['doc_ref' => 'FA002', 'numero_compte' => '401000', 'code_journal' => 'VT', 'doc_date' => now(), 'debit' => 200, 'credit' => 0, 'entity' => 1],
        ]);

        // Act
        $result = $this->service->getEntries(['doc_ref' => 'FA001'], 'doc_date', 'DESC', 25, 0, 1);

        // Assert
        $this->assertEquals(1, $result['total']);
    }

    #[Test]
    public function it_filters_by_journal_code(): void
    {
        // Arrange
        DB::table('llx_accounting_bookkeeping')->insert([
            ['doc_ref' => 'FA001', 'numero_compte' => '401000', 'code_journal' => 'VT', 'doc_date' => now(), 'debit' => 100, 'credit' => 0, 'entity' => 1],
            ['doc_ref' => 'FA002', 'numero_compte' => '401000', 'code_journal' => 'AC', 'doc_date' => now(), 'debit' => 200, 'credit' => 0, 'entity' => 1],
        ]);

        // Act
        $result = $this->service->getEntries(['code_journal' => 'VT'], 'doc_date', 'DESC', 25, 0, 1);

        // Assert
        $this->assertEquals(1, $result['total']);
    }
}
