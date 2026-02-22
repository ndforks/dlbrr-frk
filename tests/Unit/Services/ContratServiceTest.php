<?php

namespace Tests\Unit\Services;

use App\Models\Contrat;
use App\Models\Societe;
use App\Services\ContratService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(ContratService::class)]
class ContratServiceTest extends TestCase
{
    use RefreshDatabase;

    private ContratService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ContratService();
    }

    #[Test]
    public function it_lists_all_contrats(): void
    {
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        Contrat::create(['ref' => 'CTR001', 'fk_soc' => $societe->rowid, 'entity' => 1]);
        Contrat::create(['ref' => 'CTR002', 'fk_soc' => $societe->rowid, 'entity' => 1]);

        $result = $this->service->list([], 0, 25);

        $this->assertCount(2, $result['contrats']);
        $this->assertEquals(2, $result['total']);
    }

    #[Test]
    public function it_filters_contrats_by_ref(): void
    {
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        Contrat::create(['ref' => 'CTR001', 'fk_soc' => $societe->rowid, 'entity' => 1]);
        Contrat::create(['ref' => 'CTR002', 'fk_soc' => $societe->rowid, 'entity' => 1]);

        $result = $this->service->list(['all' => 'CTR001'], 0, 25);

        $this->assertCount(1, $result['contrats']);
        $this->assertEquals('CTR001', $result['contrats']->first()->ref);
    }
}
