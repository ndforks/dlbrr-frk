<?php

namespace Tests\Unit\Services;

use App\Models\Societe;
use App\Models\SupplierProposal;
use App\Services\SupplierProposalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(SupplierProposalService::class)]
class SupplierProposalServiceTest extends TestCase
{
    use RefreshDatabase;

    private SupplierProposalService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SupplierProposalService();
    }

    #[Test]
    public function it_gets_supplier_proposals_list(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Supplier', 'entity' => 1]);
        
        SupplierProposal::create([
            'ref' => 'SP001',
            'fk_soc' => $societe->rowid,
            'date_valid' => now(),
            'entity' => 1
        ]);
        
        SupplierProposal::create([
            'ref' => 'SP002',
            'fk_soc' => $societe->rowid,
            'date_valid' => now(),
            'entity' => 1
        ]);

        // Act
        $result = $this->service->getList([], 'date_valid', 'DESC', 25, 0, 1);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('proposals', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertEquals(2, $result['total']);
    }

    #[Test]
    public function it_filters_proposals_by_ref(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Supplier', 'entity' => 1]);
        SupplierProposal::create(['ref' => 'SP001', 'fk_soc' => $societe->rowid, 'date_valid' => now(), 'entity' => 1]);
        SupplierProposal::create(['ref' => 'SP002', 'fk_soc' => $societe->rowid, 'date_valid' => now(), 'entity' => 1]);

        // Act
        $result = $this->service->getList(['ref' => 'SP001'], 'date_valid', 'DESC', 25, 0, 1);

        // Assert
        $this->assertEquals(1, $result['total']);
    }

    #[Test]
    public function it_paginates_proposals(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Supplier', 'entity' => 1]);
        for ($i = 1; $i <= 30; $i++) {
            SupplierProposal::create(['ref' => "SP$i", 'fk_soc' => $societe->rowid, 'date_valid' => now(), 'entity' => 1]);
        }

        // Act
        $result = $this->service->getList([], 'date_valid', 'DESC', 10, 0, 1);

        // Assert
        $this->assertEquals(30, $result['total']);
        $this->assertCount(10, $result['proposals']);
    }
}
