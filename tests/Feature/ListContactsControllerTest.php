<?php

namespace Tests\Feature;

use App\Http\Controllers\Contact\ListContacts;
use App\Models\Contact;
use App\Models\Societe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(ListContacts::class)]
class ListContactsControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_loads_the_contacts_list_page(): void
    {
        // Arrange
        // (no setup needed)
        
        // Act
        $response = $this->get('/contact');
        
        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('contact.list');
        $response->assertViewHas('contacts');
        $response->assertViewHas('total');
    }
    
    #[Test]
    public function it_displays_contacts_with_company_information(): void
    {
        // Arrange
        $societe = Societe::create([
            'nom' => 'Test Company',
            'entity' => 1,
        ]);
        
        $contact = Contact::create([
            'lastname' => 'Doe',
            'firstname' => 'John',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'fk_soc' => $societe->rowid,
            'entity' => 1,
        ]);
        
        // Act
        $response = $this->get('/contact');
        
        // Assert
        $response->assertStatus(200);
        $response->assertSee('Doe');
        $response->assertSee('John');
        $response->assertSee('john.doe@example.com');
        $response->assertSee('Test Company');
    }
    
    #[Test]
    public function it_filters_contacts_by_lastname(): void
    {
        // Arrange
        Contact::create([
            'lastname' => 'Smith',
            'firstname' => 'Jane',
            'entity' => 1,
        ]);
        
        Contact::create([
            'lastname' => 'Johnson',
            'firstname' => 'Bob',
            'entity' => 1,
        ]);
        
        // Act
        $response = $this->get('/contact?search_lastname=Smith');
        
        // Assert
        $response->assertStatus(200);
        $response->assertSee('Smith');
        $response->assertDontSee('Johnson');
    }
    
    #[Test]
    public function it_searches_across_all_contact_fields(): void
    {
        // Arrange
        Contact::create([
            'lastname' => 'Taylor',
            'firstname' => 'Alice',
            'email' => 'alice@example.com',
            'entity' => 1,
        ]);
        
        Contact::create([
            'lastname' => 'Brown',
            'firstname' => 'Charlie',
            'email' => 'charlie@example.com',
            'entity' => 1,
        ]);
        
        // Act
        $response = $this->get('/contact/list.php?search_all=alice@');
        
        // Assert
        $response->assertStatus(200);
        $response->assertSee('Taylor');
        $response->assertDontSee('Brown');
    }
    
    #[Test]
    public function it_paginates_contacts_correctly(): void
    {
        // Arrange
        for ($i = 0; $i < 30; $i++) {
            Contact::create([
                'lastname' => 'Contact' . $i,
                'firstname' => 'Test',
                'entity' => 1,
            ]);
        }
        
        // Act
        $firstPageResponse = $this->get('/contact/list.php?limit=10');
        $secondPageResponse = $this->get('/contact/list.php?page=1&limit=10');
        
        // Assert
        $firstPageResponse->assertStatus(200);
        $firstPageResponse->assertViewHas('contacts', function($contacts) {
            return $contacts->count() === 10;
        });
        
        $secondPageResponse->assertStatus(200);
        $secondPageResponse->assertViewHas('contacts', function($contacts) {
            return $contacts->count() === 10;
        });
    }
    
    #[Test]
    public function it_shows_message_when_no_results_are_found(): void
    {
        // Arrange
        // (no contacts created)
        
        // Act
        $response = $this->get('/contact/list.php?search_lastname=NonexistentName');
        
        // Assert
        $response->assertStatus(200);
        $response->assertSee('No contacts found');
    }
    
    #[Test]
    public function it_filters_contacts_by_company_name(): void
    {
        // Arrange
        $societe1 = Societe::create([
            'nom' => 'Acme Corp',
            'entity' => 1,
        ]);
        
        $societe2 = Societe::create([
            'nom' => 'Tech Inc',
            'entity' => 1,
        ]);
        
        Contact::create([
            'lastname' => 'Acme Employee',
            'firstname' => 'John',
            'fk_soc' => $societe1->rowid,
            'entity' => 1,
        ]);
        
        Contact::create([
            'lastname' => 'Tech Employee',
            'firstname' => 'Jane',
            'fk_soc' => $societe2->rowid,
            'entity' => 1,
        ]);
        
        // Act
        $response = $this->get('/contact/list.php?search_societe=Acme');
        
        // Assert
        $response->assertStatus(200);
        $response->assertSee('Acme Employee');
        $response->assertDontSee('Tech Employee');
    }
}
