<?php

namespace Tests\Unit\Services;

use App\Models\Expedition;
use App\Models\Societe;
use App\Services\ExpeditionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(ExpeditionService::class)]
class ExpeditionServiceTest extends TestCase
{
    use RefreshDatabase;

    private ExpeditionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ExpeditionService();
    }

    #[Test]
    public function it_lists_all_expeditions(): void
    {
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        Expedition::create(['ref' => 'EXP001', 'fk_soc' => $societe->rowid, 'date_expedition' => now(), 'entity' => 1]);
        Expedition::create(['ref' => 'EXP002', 'fk_soc' => $societe->rowid, 'date_expedition' => now(), 'entity' => 1]);

        $result = $this->service->list([], 0, 25);

        $this->assertCount(2, $result['expeditions']);
        $this->assertEquals(2, $result['total']);
    }

    #[Test]
    public function it_filters_expeditions_by_ref(): void
    {
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        Expedition::create(['ref' => 'EXP001', 'fk_soc' => $societe->rowid, 'date_expedition' => now(), 'entity' => 1]);
        Expedition::create(['ref' => 'EXP002', 'fk_soc' => $societe->rowid, 'date_expedition' => now(), 'entity' => 1]);

        $result = $this->service->list(['all' => 'EXP001'], 0, 25);

        $this->assertCount(1, $result['expeditions']);
        $this->assertEquals('EXP001', $result['expeditions']->first()->ref);
    }
}
