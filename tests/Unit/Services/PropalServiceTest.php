<?php

namespace Tests\Unit\Services;

use App\Models\Propal;
use App\Models\Societe;
use App\Services\PropalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(PropalService::class)]
class PropalServiceTest extends TestCase
{
    use RefreshDatabase;

    private PropalService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PropalService();
    }

    #[Test]
    public function it_lists_all_propals(): void
    {
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        Propal::create(['ref' => 'PROP001', 'fk_soc' => $societe->rowid, 'datep' => now(), 'entity' => 1]);
        Propal::create(['ref' => 'PROP002', 'fk_soc' => $societe->rowid, 'datep' => now(), 'entity' => 1]);

        $result = $this->service->list([], 0, 25);

        $this->assertCount(2, $result['propals']);
        $this->assertEquals(2, $result['total']);
    }

    #[Test]
    public function it_filters_propals_by_ref(): void
    {
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        Propal::create(['ref' => 'PROP001', 'fk_soc' => $societe->rowid, 'datep' => now(), 'entity' => 1]);
        Propal::create(['ref' => 'PROP002', 'fk_soc' => $societe->rowid, 'datep' => now(), 'entity' => 1]);

        $result = $this->service->list(['ref' => 'PROP001'], 0, 25);

        $this->assertCount(1, $result['propals']);
        $this->assertEquals('PROP001', $result['propals']->first()->ref);
    }
}
