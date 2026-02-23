<?php

namespace Tests\Unit\Services;

use App\Models\Commande;
use App\Models\Societe;
use App\Services\CommandeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(CommandeService::class)]
class CommandeServiceTest extends TestCase
{
    use RefreshDatabase;

    private CommandeService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CommandeService();
    }

    #[Test]
    public function it_lists_all_commandes(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        Commande::create(['ref' => 'CMD001', 'fk_soc' => $societe->rowid, 'date_commande' => now(), 'entity' => 1]);
        Commande::create(['ref' => 'CMD002', 'fk_soc' => $societe->rowid, 'date_commande' => now(), 'entity' => 1]);

        // Act
        $result = $this->service->list([], 0, 25);

        // Assert
        $this->assertCount(2, $result['commandes']);
        $this->assertEquals(2, $result['total']);
    }

    #[Test]
    public function it_filters_commandes_by_ref(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        Commande::create(['ref' => 'CMD001', 'fk_soc' => $societe->rowid, 'date_commande' => now(), 'entity' => 1]);
        Commande::create(['ref' => 'CMD002', 'fk_soc' => $societe->rowid, 'date_commande' => now(), 'entity' => 1]);

        // Act
        $result = $this->service->list(['ref' => 'CMD001'], 0, 25);

        // Assert
        $this->assertCount(1, $result['commandes']);
        $this->assertEquals('CMD001', $result['commandes']->first()->ref);
    }

    #[Test]
    public function it_filters_commandes_by_societe(): void
    {
        // Arrange
        $societe1 = Societe::create(['nom' => 'Acme Corp', 'entity' => 1]);
        $societe2 = Societe::create(['nom' => 'Test Corp', 'entity' => 1]);
        Commande::create(['ref' => 'CMD001', 'fk_soc' => $societe1->rowid, 'date_commande' => now(), 'entity' => 1]);
        Commande::create(['ref' => 'CMD002', 'fk_soc' => $societe2->rowid, 'date_commande' => now(), 'entity' => 1]);

        // Act
        $result = $this->service->list(['societe' => 'Acme'], 0, 25);

        // Assert
        $this->assertCount(1, $result['commandes']);
        $this->assertEquals('CMD001', $result['commandes']->first()->ref);
    }

    #[Test]
    public function it_paginates_commandes(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        for ($i = 0; $i < 30; $i++) {
            Commande::create(['ref' => "CMD00$i", 'fk_soc' => $societe->rowid, 'date_commande' => now(), 'entity' => 1]);
        }

        // Act
        $result = $this->service->list([], 0, 10);

        // Assert
        $this->assertCount(10, $result['commandes']);
        $this->assertEquals(30, $result['total']);
        $this->assertEquals(0, $result['page']);
        $this->assertEquals(10, $result['limit']);
    }
}
