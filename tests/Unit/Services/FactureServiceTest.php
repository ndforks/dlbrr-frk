<?php

namespace Tests\Unit\Services;

use App\Models\Facture;
use App\Models\Societe;
use App\Services\FactureService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(FactureService::class)]
class FactureServiceTest extends TestCase
{
    use RefreshDatabase;

    private FactureService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new FactureService();
    }

    #[Test]
    public function it_lists_all_factures(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        Facture::create(['ref' => 'FA001', 'fk_soc' => $societe->rowid, 'datef' => now(), 'entity' => 1]);
        Facture::create(['ref' => 'FA002', 'fk_soc' => $societe->rowid, 'datef' => now(), 'entity' => 1]);

        // Act
        $result = $this->service->list([], 0, 25);

        // Assert
        $this->assertCount(2, $result['factures']);
        $this->assertEquals(2, $result['total']);
    }

    #[Test]
    public function it_filters_factures_by_ref(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        Facture::create(['ref' => 'FA001', 'fk_soc' => $societe->rowid, 'datef' => now(), 'entity' => 1]);
        Facture::create(['ref' => 'FA002', 'fk_soc' => $societe->rowid, 'datef' => now(), 'entity' => 1]);

        // Act
        $result = $this->service->list(['ref' => 'FA001'], 0, 25);

        // Assert
        $this->assertCount(1, $result['factures']);
        $this->assertEquals('FA001', $result['factures']->first()->ref);
    }

    #[Test]
    public function it_filters_factures_by_societe(): void
    {
        // Arrange
        $societe1 = Societe::create(['nom' => 'Acme Corp', 'entity' => 1]);
        $societe2 = Societe::create(['nom' => 'Test Corp', 'entity' => 1]);
        Facture::create(['ref' => 'FA001', 'fk_soc' => $societe1->rowid, 'datef' => now(), 'entity' => 1]);
        Facture::create(['ref' => 'FA002', 'fk_soc' => $societe2->rowid, 'datef' => now(), 'entity' => 1]);

        // Act
        $result = $this->service->list(['societe' => 'Acme'], 0, 25);

        // Assert
        $this->assertCount(1, $result['factures']);
        $this->assertEquals('FA001', $result['factures']->first()->ref);
    }

    #[Test]
    public function it_paginates_factures(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        for ($i = 0; $i < 30; $i++) {
            Facture::create(['ref' => "FA00$i", 'fk_soc' => $societe->rowid, 'datef' => now(), 'entity' => 1]);
        }

        // Act
        $result = $this->service->list([], 0, 10);

        // Assert
        $this->assertCount(10, $result['factures']);
        $this->assertEquals(30, $result['total']);
        $this->assertEquals(0, $result['page']);
        $this->assertEquals(10, $result['limit']);
    }
}
