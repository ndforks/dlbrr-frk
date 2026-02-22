<?php

namespace Tests\Feature\Projet;

use App\Http\Controllers\Projet\ShowProjet;
use App\Models\Projet;
use App\Models\Societe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(ShowProjet::class)]
class ShowProjetControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_shows_a_projet(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        $projet = Projet::create([
            'ref' => 'PRJ001',
            'title' => 'Test Project',
            'fk_soc' => $societe->rowid,
            'entity' => 1,
        ]);

        // Act
        $response = $this->get(route('projet.show', ['id' => $projet->rowid]));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('projet.show');
        $response->assertViewHas('projet');
        $this->assertEquals($projet->rowid, $response->viewData('projet')->rowid);
    }

    #[Test]
    public function it_displays_create_form(): void
    {
        // Act
        $response = $this->get(route('projet.show', ['action' => 'create']));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('projet.create');
    }

    #[Test]
    public function it_displays_edit_form(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        $projet = Projet::create([
            'ref' => 'PRJ001',
            'title' => 'Test Project',
            'fk_soc' => $societe->rowid,
            'entity' => 1,
        ]);

        // Act
        $response = $this->get(route('projet.show', [
            'action' => 'edit',
            'id' => $projet->rowid,
        ]));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('projet.edit');
        $response->assertViewHas('projet');
    }

    #[Test]
    public function it_updates_a_projet(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        $projet = Projet::create([
            'ref' => 'PRJ001',
            'title' => 'Old Title',
            'fk_soc' => $societe->rowid,
            'entity' => 1,
        ]);

        // Act
        $response = $this->post(route('projet.show', [
            'action' => 'update',
            'id' => $projet->rowid,
        ]), [
            'ref' => 'PRJ001',
            'title' => 'New Title',
            'description' => 'Updated description',
        ]);

        // Assert
        $response->assertRedirect(route('projet.show', ['id' => $projet->rowid]));
        $projet->refresh();
        $this->assertEquals('New Title', $projet->title);
    }

    #[Test]
    public function it_deletes_a_projet(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        $projet = Projet::create([
            'ref' => 'PRJ001',
            'title' => 'Test Project',
            'fk_soc' => $societe->rowid,
            'entity' => 1,
        ]);

        // Act
        $response = $this->post(route('projet.show', [
            'action' => 'delete',
            'id' => $projet->rowid,
        ]));

        // Assert
        $response->assertRedirect(route('projet.list'));
        $this->assertDatabaseMissing('llx_projet', [
            'rowid' => $projet->rowid,
        ]);
    }
}
