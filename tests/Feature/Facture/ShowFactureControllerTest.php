<?php
/* Copyright (C) 2024-2025 Copilot
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

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
        $societe = Societe::factory()->create();
        $facture = Facture::factory()->create([
            'ref' => 'FA001',
            'fk_soc' => $societe->rowid,
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
        $societe = Societe::factory()->create();
        $facture = Facture::factory()->create([
            'ref' => 'FA001',
            'fk_soc' => $societe->rowid,
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
        $societe = Societe::factory()->create();
        $facture = Facture::factory()->create([
            'ref' => 'FA001',
            'fk_soc' => $societe->rowid,
        ]);

        // Act
        $response = $this->post(route('facture.show', [
            'action' => 'update',
            'id' => $facture->rowid,
        ]), [
            'ref' => 'FA001-UPDATED',
            'ref_client' => 'CLIENT-REF',
            'socid' => $societe->rowid, // Include company id to prevent fk_soc reset
        ]);

        // Assert
        $response->assertRedirect(route('facture.show', ['id' => $facture->rowid]));
        $facture->refresh();
        $this->assertEquals('FA001-UPDATED', $facture->ref);
        $this->assertEquals($societe->rowid, $facture->fk_soc);
    }

    #[Test]
    public function it_deletes_a_facture(): void
    {
        // Arrange
        $societe = Societe::factory()->create();
        $facture = Facture::factory()->create([
            'ref' => 'FA001',
            'fk_soc' => $societe->rowid,
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
