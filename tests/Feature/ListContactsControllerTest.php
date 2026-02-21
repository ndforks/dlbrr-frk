<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Societe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListContactsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_contacts_list_page_loads(): void
    {
        $response = $this->get('/contact/list.php');
        
        $response->assertStatus(200);
        $response->assertViewIs('contact.list');
        $response->assertViewHas('contacts');
        $response->assertViewHas('total');
    }
    
    public function test_contacts_are_displayed(): void
    {
        // Create test data
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
        
        $response = $this->get('/contact/list.php');
        
        $response->assertStatus(200);
        $response->assertSee('Doe');
        $response->assertSee('John');
        $response->assertSee('john.doe@example.com');
        $response->assertSee('Test Company');
    }
    
    public function test_search_by_lastname_works(): void
    {
        // Create test contacts
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
        
        $response = $this->get('/contact/list.php?search_lastname=Smith');
        
        $response->assertStatus(200);
        $response->assertSee('Smith');
        $response->assertDontSee('Johnson');
    }
    
    public function test_search_all_fields_works(): void
    {
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
        
        // Search by email should find only Alice
        $response = $this->get('/contact/list.php?search_all=alice@');
        
        $response->assertStatus(200);
        $response->assertSee('Taylor');
        $response->assertDontSee('Brown');
    }
    
    public function test_pagination_works(): void
    {
        // Create multiple contacts
        for ($i = 0; $i < 30; $i++) {
            Contact::create([
                'lastname' => 'Contact' . $i,
                'firstname' => 'Test',
                'entity' => 1,
            ]);
        }
        
        // First page
        $response = $this->get('/contact/list.php?limit=10');
        $response->assertStatus(200);
        $response->assertViewHas('contacts', function($contacts) {
            return $contacts->count() === 10;
        });
        
        // Second page
        $response = $this->get('/contact/list.php?page=1&limit=10');
        $response->assertStatus(200);
        $response->assertViewHas('contacts', function($contacts) {
            return $contacts->count() === 10;
        });
    }
    
    public function test_no_results_message_displayed(): void
    {
        $response = $this->get('/contact/list.php?search_lastname=NonexistentName');
        
        $response->assertStatus(200);
        $response->assertSee('No contacts found');
    }
    
    public function test_search_by_company_works(): void
    {
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
        
        $response = $this->get('/contact/list.php?search_societe=Acme');
        
        $response->assertStatus(200);
        $response->assertSee('Acme Employee');
        $response->assertDontSee('Tech Employee');
    }
}
