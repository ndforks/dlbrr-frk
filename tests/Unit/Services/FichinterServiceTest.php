<?php

namespace Tests\Unit\Services;

use App\Models\Fichinter;
use App\Models\Societe;
use App\Services\FichinterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(FichinterService::class)]
class FichinterServiceTest extends TestCase
{
    use RefreshDatabase;

    private FichinterService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new FichinterService();
    }

    #[Test]
    public function it_lists_all_fichinters(): void
    {
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        Fichinter::create(['ref' => 'FI001', 'fk_soc' => $societe->rowid, 'entity' => 1]);
        Fichinter::create(['ref' => 'FI002', 'fk_soc' => $societe->rowid, 'entity' => 1]);

        $result = $this->service->list([], 0, 25);

        $this->assertCount(2, $result['fichinters']);
        $this->assertEquals(2, $result['total']);
    }

    #[Test]
    public function it_filters_fichinters_by_ref(): void
    {
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        Fichinter::create(['ref' => 'FI001', 'fk_soc' => $societe->rowid, 'entity' => 1]);
        Fichinter::create(['ref' => 'FI002', 'fk_soc' => $societe->rowid, 'entity' => 1]);

        $result = $this->service->list(['all' => 'FI001'], 0, 25);

        $this->assertCount(1, $result['fichinters']);
        $this->assertEquals('FI001', $result['fichinters']->first()->ref);
    }
}
