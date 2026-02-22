<?php

namespace Tests\Unit\Services;

use App\Models\Societe;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(TicketService::class)]
class TicketServiceTest extends TestCase
{
    use RefreshDatabase;

    private TicketService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TicketService();
    }

    #[Test]
    public function it_lists_all_tickets(): void
    {
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        Ticket::create(['ref' => 'TIC001', 'subject' => 'Issue 1', 'fk_soc' => $societe->rowid, 'datec' => now(), 'entity' => 1]);
        Ticket::create(['ref' => 'TIC002', 'subject' => 'Issue 2', 'fk_soc' => $societe->rowid, 'datec' => now(), 'entity' => 1]);

        $result = $this->service->list([], 0, 25);

        $this->assertCount(2, $result['tickets']);
        $this->assertEquals(2, $result['total']);
    }

    #[Test]
    public function it_filters_tickets_by_ref(): void
    {
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        Ticket::create(['ref' => 'TIC001', 'subject' => 'Issue 1', 'fk_soc' => $societe->rowid, 'datec' => now(), 'entity' => 1]);
        Ticket::create(['ref' => 'TIC002', 'subject' => 'Issue 2', 'fk_soc' => $societe->rowid, 'datec' => now(), 'entity' => 1]);

        $result = $this->service->list(['ref' => 'TIC001'], 0, 25);

        $this->assertCount(1, $result['tickets']);
        $this->assertEquals('TIC001', $result['tickets']->first()->ref);
    }
}
