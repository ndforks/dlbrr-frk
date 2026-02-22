<?php

namespace Tests\Feature\Facture;

use App\Http\Controllers\Compta\Facture\ShowFacture;
use App\Models\Facture;
use App\Models\Societe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(ShowFacture::class)]
class ShowFactureControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_shows_a_facture(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        $facture = Facture::create([
            'ref' => 'FA001',
            'fk_soc' => $societe->rowid,
            'datef' => now(),
            'entity' => 1,
        ]);

        // Act
        $response = $this->get(route('facture.show', ['id' => $facture->rowid]));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('facture.show');
        $response->assertViewHas('facture');
        $this->assertEquals($facture->rowid, $response->viewData('facture')->rowid);
    }

    #[Test]
    public function it_displays_create_form(): void
    {
        // Act
        $response = $this->get(route('facture.show', ['action' => 'create']));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('facture.create');
    }

    #[Test]
    public function it_displays_edit_form(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        $facture = Facture::create([
            'ref' => 'FA001',
            'fk_soc' => $societe->rowid,
            'datef' => now(),
            'entity' => 1,
        ]);

        // Act
        $response = $this->get(route('facture.show', [
            'action' => 'edit',
            'id' => $facture->rowid,
        ]));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('facture.edit');
        $response->assertViewHas('facture');
    }

    #[Test]
    public function it_updates_a_facture(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        $facture = Facture::create([
            'ref' => 'FA001',
            'fk_soc' => $societe->rowid,
            'datef' => now(),
            'entity' => 1,
        ]);

        // Act
        $response = $this->post(route('facture.show', [
            'action' => 'update',
            'id' => $facture->rowid,
        ]), [
            'ref' => 'FA001-UPDATED',
            'ref_client' => 'CLIENT-REF',
        ]);

        // Assert
        $response->assertRedirect(route('facture.show', ['id' => $facture->rowid]));
        $facture->refresh();
        $this->assertEquals('FA001-UPDATED', $facture->ref);
    }

    #[Test]
    public function it_deletes_a_facture(): void
    {
        // Arrange
        $societe = Societe::create(['nom' => 'Test Company', 'entity' => 1]);
        $facture = Facture::create([
            'ref' => 'FA001',
            'fk_soc' => $societe->rowid,
            'datef' => now(),
            'entity' => 1,
        ]);

        // Act
        $response = $this->post(route('facture.show', [
            'action' => 'delete',
            'id' => $facture->rowid,
        ]));

        // Assert
        $response->assertRedirect(route('facture.list'));
        $this->assertDatabaseMissing('llx_facture', [
            'rowid' => $facture->rowid,
        ]);
    }
}
