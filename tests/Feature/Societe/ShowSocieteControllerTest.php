<?php

namespace Tests\Feature\Societe;

use App\Http\Controllers\Societe\ShowSociete;
use App\Models\Societe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(ShowSociete::class)]
class ShowSocieteControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_shows_a_societe(): void
    {
        // Arrange
        $societe = Societe::create([
            'nom' => 'Test Company',
            'entity' => 1,
        ]);

        // Act
        $response = $this->get(route('societe.show', ['id' => $societe->rowid]));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('societe.show');
        $response->assertViewHas('societe');
        $this->assertEquals($societe->rowid, $response->viewData('societe')->rowid);
    }

    #[Test]
    public function it_displays_create_form(): void
    {
        // Act
        $response = $this->get(route('societe.show', ['action' => 'create']));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('societe.create');
    }

    #[Test]
    public function it_displays_edit_form(): void
    {
        // Arrange
        $societe = Societe::create([
            'nom' => 'Test Company',
            'entity' => 1,
        ]);

        // Act
        $response = $this->get(route('societe.show', [
            'action' => 'edit',
            'id' => $societe->rowid,
        ]));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('societe.edit');
        $response->assertViewHas('societe');
    }

    #[Test]
    public function it_updates_a_societe(): void
    {
        // Arrange
        $societe = Societe::create([
            'nom' => 'Old Company',
            'entity' => 1,
        ]);

        // Act
        $response = $this->post(route('societe.show', [
            'action' => 'update',
            'id' => $societe->rowid,
        ]), [
            'nom' => 'New Company',
            'address' => '123 Main St',
            'town' => 'Paris',
            'zip' => '75000',
            'email' => 'test@example.com',
        ]);

        // Assert
        $response->assertRedirect(route('societe.show', ['id' => $societe->rowid]));
        $societe->refresh();
        $this->assertEquals('New Company', $societe->nom);
        $this->assertEquals('Paris', $societe->town);
    }

    #[Test]
    public function it_deletes_a_societe(): void
    {
        // Arrange
        $societe = Societe::create([
            'nom' => 'Test Company',
            'entity' => 1,
        ]);

        // Act
        $response = $this->post(route('societe.show', [
            'action' => 'delete',
            'id' => $societe->rowid,
        ]));

        // Assert
        $response->assertRedirect(route('societe.list'));
        $this->assertDatabaseMissing('llx_societe', [
            'rowid' => $societe->rowid,
        ]);
    }
}
