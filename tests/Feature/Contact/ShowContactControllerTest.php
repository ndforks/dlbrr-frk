<?php

namespace Tests\Feature\Contact;

use App\Http\Controllers\Contact\ShowContact;
use App\Models\Contact;
use App\Models\Societe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(ShowContact::class)]
class ShowContactControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_shows_a_contact(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        $contact = Contact::create([
            'lastname' => 'Doe',
            'firstname' => 'John',
            'fk_soc' => $societe->rowid,
            'entity' => 1,
        ]);

        // Act
        $response = $this->get(route('contact.show', ['id' => $contact->rowid]));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('contact.show');
        $response->assertViewHas('contact');
        $this->assertEquals($contact->rowid, $response->viewData('contact')->rowid);
    }

    #[Test]
    public function it_displays_create_form(): void
    {
        // Act
        $response = $this->get(route('contact.show', ['action' => 'create']));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('contact.create');
    }

    #[Test]
    public function it_displays_edit_form(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        $contact = Contact::create([
            'lastname' => 'Doe',
            'firstname' => 'John',
            'fk_soc' => $societe->rowid,
            'entity' => 1,
        ]);

        // Act
        $response = $this->get(route('contact.show', [
            'action' => 'edit',
            'id' => $contact->rowid,
        ]));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('contact.edit');
        $response->assertViewHas('contact');
    }

    #[Test]
    public function it_updates_a_contact(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        $contact = Contact::create([
            'lastname' => 'Doe',
            'firstname' => 'John',
            'fk_soc' => $societe->rowid,
            'entity' => 1,
        ]);

        // Act
        $response = $this->post(route('contact.show', [
            'action' => 'update',
            'id' => $contact->rowid,
        ]), [
            'lastname' => 'Smith',
            'firstname' => 'Jane',
            'email' => 'jane@example.com',
        ]);

        // Assert
        $response->assertRedirect(route('contact.show', ['id' => $contact->rowid]));
        $contact->refresh();
        $this->assertEquals('Smith', $contact->lastname);
        $this->assertEquals('Jane', $contact->firstname);
    }

    #[Test]
    public function it_deletes_a_contact(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        $contact = Contact::create([
            'lastname' => 'Doe',
            'firstname' => 'John',
            'fk_soc' => $societe->rowid,
            'entity' => 1,
        ]);

        // Act
        $response = $this->post(route('contact.show', [
            'action' => 'delete',
            'id' => $contact->rowid,
        ]));

        // Assert
        $response->assertRedirect(route('contact.list'));
        $this->assertDatabaseMissing('llx_socpeople', [
            'rowid' => $contact->rowid,
        ]);
    }
}
