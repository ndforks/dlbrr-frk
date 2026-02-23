<?php

namespace Tests\Unit\Services;

use App\Models\Don;
use App\Models\Societe;
use App\Services\DonService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(DonService::class)]
class DonServiceTest extends TestCase
{
    use RefreshDatabase;

    private DonService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DonService();
    }

    #[Test]
    public function it_lists_all_dons(): void
    {
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        Don::create(['ref' => 'DON001', 'fk_soc' => $societe->rowid, 'amount' => 100, 'datedon' => now(), 'entity' => 1]);
        Don::create(['ref' => 'DON002', 'fk_soc' => $societe->rowid, 'amount' => 200, 'datedon' => now(), 'entity' => 1]);

        $result = $this->service->list([], 0, 25);

        $this->assertCount(2, $result['dons']);
        $this->assertEquals(2, $result['total']);
    }

    #[Test]
    public function it_filters_dons_by_ref(): void
    {
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        Don::create(['ref' => 'DON001', 'fk_soc' => $societe->rowid, 'amount' => 100, 'datedon' => now(), 'entity' => 1]);
        Don::create(['ref' => 'DON002', 'fk_soc' => $societe->rowid, 'amount' => 200, 'datedon' => now(), 'entity' => 1]);

        $result = $this->service->list(['all' => 'DON001'], 0, 25);

        $this->assertCount(1, $result['dons']);
        $this->assertEquals('DON001', $result['dons']->first()->ref);
    }
}
