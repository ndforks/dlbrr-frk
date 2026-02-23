<?php

namespace Tests\Unit\Services;

use App\Models\Projet;
use App\Models\Societe;
use App\Services\ProjetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(ProjetService::class)]
class ProjetServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProjetService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ProjetService();
    }

    #[Test]
    public function it_lists_all_projets(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        Projet::create(['ref' => 'PRJ001', 'title' => 'Project 1', 'fk_soc' => $societe->rowid, 'entity' => 1]);
        Projet::create(['ref' => 'PRJ002', 'title' => 'Project 2', 'fk_soc' => $societe->rowid, 'entity' => 1]);

        // Act
        $result = $this->service->list([], 0, 25);

        // Assert
        $this->assertCount(2, $result['projets']);
        $this->assertEquals(2, $result['total']);
    }

    #[Test]
    public function it_filters_projets_by_ref(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        Projet::create(['ref' => 'PRJ001', 'title' => 'Project 1', 'fk_soc' => $societe->rowid, 'entity' => 1]);
        Projet::create(['ref' => 'PRJ002', 'title' => 'Project 2', 'fk_soc' => $societe->rowid, 'entity' => 1]);

        // Act
        $result = $this->service->list(['ref' => 'PRJ001'], 0, 25);

        // Assert
        $this->assertCount(1, $result['projets']);
        $this->assertEquals('PRJ001', $result['projets']->first()->ref);
    }

    #[Test]
    public function it_filters_projets_by_title(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        Projet::create(['ref' => 'PRJ001', 'title' => 'Web Application', 'fk_soc' => $societe->rowid, 'entity' => 1]);
        Projet::create(['ref' => 'PRJ002', 'title' => 'Mobile App', 'fk_soc' => $societe->rowid, 'entity' => 1]);

        // Act
        $result = $this->service->list(['title' => 'Web'], 0, 25);

        // Assert
        $this->assertCount(1, $result['projets']);
        $this->assertEquals('Web Application', $result['projets']->first()->title);
    }

    #[Test]
    public function it_paginates_projets(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        for ($i = 0; $i < 30; $i++) {
            Projet::create(['ref' => "PRJ00$i", 'title' => "Project $i", 'fk_soc' => $societe->rowid, 'entity' => 1]);
        }

        // Act
        $result = $this->service->list([], 0, 10);

        // Assert
        $this->assertCount(10, $result['projets']);
        $this->assertEquals(30, $result['total']);
        $this->assertEquals(0, $result['page']);
        $this->assertEquals(10, $result['limit']);
    }
}
