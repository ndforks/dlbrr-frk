<?php

namespace Tests\Unit\Services;

use App\Models\Contact;
use App\Models\Societe;
use App\Services\ContactService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(ContactService::class)]
class ContactServiceTest extends TestCase
{
    use RefreshDatabase;

    private ContactService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ContactService();
    }

    #[Test]
    public function it_lists_all_contacts(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        Contact::create(['lastname' => 'Doe', 'firstname' => 'John', 'fk_soc' => $societe->rowid, 'entity' => 1]);
        Contact::create(['lastname' => 'Smith', 'firstname' => 'Jane', 'fk_soc' => $societe->rowid, 'entity' => 1]);

        // Act
        $result = $this->service->list([], 0, 25);

        // Assert
        $this->assertCount(2, $result['contacts']);
        $this->assertEquals(2, $result['total']);
    }

    #[Test]
    public function it_filters_contacts_by_lastname(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        Contact::create(['lastname' => 'Doe', 'firstname' => 'John', 'fk_soc' => $societe->rowid, 'entity' => 1]);
        Contact::create(['lastname' => 'Smith', 'firstname' => 'Jane', 'fk_soc' => $societe->rowid, 'entity' => 1]);

        // Act
        $result = $this->service->list(['lastname' => 'Doe'], 0, 25);

        // Assert
        $this->assertCount(1, $result['contacts']);
        $this->assertEquals('Doe', $result['contacts']->first()->lastname);
    }

    #[Test]
    public function it_filters_contacts_by_email(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        Contact::create(['lastname' => 'Doe', 'email' => 'john@example.com', 'fk_soc' => $societe->rowid, 'entity' => 1]);
        Contact::create(['lastname' => 'Smith', 'email' => 'jane@example.com', 'fk_soc' => $societe->rowid, 'entity' => 1]);

        // Act
        $result = $this->service->list(['email' => 'john'], 0, 25);

        // Assert
        $this->assertCount(1, $result['contacts']);
        $this->assertEquals('john@example.com', $result['contacts']->first()->email);
    }

    #[Test]
    public function it_filters_contacts_by_societe(): void
    {
        // Arrange
        $societe1 = Societe::create(['nom' => 'Acme Corp', 'entity' => 1]);
        $societe2 = Societe::create(['nom' => 'Test Corp', 'entity' => 1]);
        Contact::create(['lastname' => 'Doe', 'fk_soc' => $societe1->rowid, 'entity' => 1]);
        Contact::create(['lastname' => 'Smith', 'fk_soc' => $societe2->rowid, 'entity' => 1]);

        // Act
        $result = $this->service->list(['societe' => 'Acme'], 0, 25);

        // Assert
        $this->assertCount(1, $result['contacts']);
        $this->assertEquals('Doe', $result['contacts']->first()->lastname);
    }

    #[Test]
    public function it_paginates_contacts(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        for ($i = 0; $i < 30; $i++) {
            Contact::create(['lastname' => "Contact$i", 'fk_soc' => $societe->rowid, 'entity' => 1]);
        }

        // Act
        $result = $this->service->list([], 0, 10);

        // Assert
        $this->assertCount(10, $result['contacts']);
        $this->assertEquals(30, $result['total']);
        $this->assertEquals(0, $result['page']);
        $this->assertEquals(10, $result['limit']);
    }
}
