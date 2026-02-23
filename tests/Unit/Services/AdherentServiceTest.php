<?php

namespace Tests\Unit\Services;

use App\Models\Adherent;
use App\Services\AdherentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(AdherentService::class)]
class AdherentServiceTest extends TestCase
{
    use RefreshDatabase;

    private AdherentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AdherentService();
    }

    #[Test]
    public function it_lists_all_adherents(): void
    {
        Adherent::create(['firstname' => 'John', 'lastname' => 'Doe', 'entity' => 1]);
        Adherent::create(['firstname' => 'Jane', 'lastname' => 'Smith', 'entity' => 1]);

        $result = $this->service->list([], 0, 25);

        $this->assertCount(2, $result['adherents']);
        $this->assertEquals(2, $result['total']);
    }

    #[Test]
    public function it_filters_adherents_by_name(): void
    {
        Adherent::create(['firstname' => 'John', 'lastname' => 'Doe', 'entity' => 1]);
        Adherent::create(['firstname' => 'Jane', 'lastname' => 'Smith', 'entity' => 1]);

        $result = $this->service->list(['all' => 'John'], 0, 25);

        $this->assertCount(1, $result['adherents']);
        $this->assertEquals('John', $result['adherents']->first()->firstname);
    }
}
