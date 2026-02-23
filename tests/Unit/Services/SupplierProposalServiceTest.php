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
        $societe = Societe::factory()->create();
        
        SupplierProposal::factory()->create([
            'ref' => 'SP001',
            'fk_soc' => $societe->rowid,
        ]);
        
        SupplierProposal::factory()->create([
            'ref' => 'SP002',
            'fk_soc' => $societe->rowid,
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
        $societe = Societe::factory()->create();
        SupplierProposal::factory()->create(['ref' => 'SP001', 'fk_soc' => $societe->rowid]);
        SupplierProposal::factory()->create(['ref' => 'SP002', 'fk_soc' => $societe->rowid]);

        // Act
        $result = $this->service->getList(['ref' => 'SP001'], 'date_valid', 'DESC', 25, 0, 1);

        // Assert
        $this->assertEquals(1, $result['total']);
    }

    #[Test]
    public function it_paginates_proposals(): void
    {
        // Arrange
        $societe = Societe::factory()->create();
        SupplierProposal::factory()->count(30)->create(['fk_soc' => $societe->rowid]);

        // Act
        $result = $this->service->getList([], 'date_valid', 'DESC', 10, 0, 1);

        // Assert
        $this->assertEquals(30, $result['total']);
        $this->assertCount(10, $result['proposals']);
    }

    #[Test]
    public function it_gets_supplier_proposal_by_id(): void
    {
        // Arrange
        $proposal = SupplierProposal::factory()->create();

        // Act
        $result = $this->service->getById($proposal->rowid);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($proposal->rowid, $result->rowid);
    }

    #[Test]
    public function it_returns_null_for_nonexistent_id(): void
    {
        // Act
        $result = $this->service->getById(99999);

        // Assert
        $this->assertNull($result);
    }

    #[Test]
    public function it_gets_supplier_proposal_by_ref(): void
    {
        // Arrange
        $proposal = SupplierProposal::factory()->create(['ref' => 'SP-TEST-001']);

        // Act
        $result = $this->service->getByRef('SP-TEST-001');

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('SP-TEST-001', $result->ref);
    }

    #[Test]
    public function it_gets_proposals_by_supplier(): void
    {
        // Arrange
        $societe = Societe::factory()->create();
        SupplierProposal::factory()->count(3)->create(['fk_soc' => $societe->rowid]);
        SupplierProposal::factory()->count(2)->create(); // Other supplier

        // Act
        $result = $this->service->getBySupplier($societe->rowid, 1);

        // Assert
        $this->assertCount(3, $result);
    }

    #[Test]
    public function it_creates_a_supplier_proposal(): void
    {
        // Arrange
        $societe = Societe::factory()->create();
        $data = [
            'ref' => 'SP-CREATE-001',
            'fk_soc' => $societe->rowid,
            'date_valid' => now()->toDateString(),
            'entity' => 1,
        ];

        // Act
        $result = $this->service->create($data);

        // Assert
        $this->assertInstanceOf(SupplierProposal::class, $result);
        $this->assertEquals('SP-CREATE-001', $result->ref);
        $this->assertDatabaseHas('llx_supplier_proposal', ['ref' => 'SP-CREATE-001']);
    }

    #[Test]
    public function it_updates_a_supplier_proposal(): void
    {
        // Arrange
        $proposal = SupplierProposal::factory()->create(['ref' => 'SP-OLD']);

        // Act
        $result = $this->service->update($proposal->rowid, ['ref' => 'SP-NEW']);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('llx_supplier_proposal', ['ref' => 'SP-NEW']);
    }

    #[Test]
    public function it_deletes_a_supplier_proposal(): void
    {
        // Arrange
        $proposal = SupplierProposal::factory()->create();
        $proposalId = $proposal->rowid;

        // Act
        $result = $this->service->delete($proposalId);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('llx_supplier_proposal', ['rowid' => $proposalId]);
    }

    #[Test]
    public function it_updates_status(): void
    {
        // Arrange
        $proposal = SupplierProposal::factory()->create(['fk_statut' => 0]);

        // Act
        $result = $this->service->updateStatus($proposal->rowid, 1);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('llx_supplier_proposal', [
            'rowid' => $proposal->rowid,
            'fk_statut' => 1,
        ]);
    }

    #[Test]
    public function it_gets_statistics(): void
    {
        // Arrange
        SupplierProposal::factory()->count(5)->create(['entity' => 1]);

        // Act
        $result = $this->service->getStatistics(1);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('total', $result);
        $this->assertEquals(5, $result['total']);
    }
}
