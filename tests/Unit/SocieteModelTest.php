<?php

namespace Tests\Unit;

use App\Models\Societe;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SocieteModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_societe_can_be_created(): void
    {
        $societe = Societe::create([
            'nom' => 'Test Company',
            'entity' => 1,
            'client' => 1,
            'fournisseur' => 0,
        ]);

        $this->assertInstanceOf(Societe::class, $societe);
        $this->assertEquals('Test Company', $societe->nom);
        $this->assertEquals(1, $societe->entity);
    }

    public function test_societe_has_commandes_relationship(): void
    {
        $societe = Societe::create([
            'nom' => 'Test Company',
            'entity' => 1,
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $societe->commandes());
    }

    public function test_societe_has_factures_relationship(): void
    {
        $societe = Societe::create([
            'nom' => 'Test Company',
            'entity' => 1,
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $societe->factures());
    }

    public function test_societe_has_projets_relationship(): void
    {
        $societe = Societe::create([
            'nom' => 'Test Company',
            'entity' => 1,
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $societe->projets());
    }

    public function test_societe_model_uses_correct_table(): void
    {
        $societe = new Societe();
        $this->assertEquals('llx_societe', $societe->getTable());
    }

    public function test_societe_model_uses_correct_primary_key(): void
    {
        $societe = new Societe();
        $this->assertEquals('rowid', $societe->getKeyName());
    }

    public function test_societe_model_has_timestamps_disabled(): void
    {
        $societe = new Societe();
        $this->assertFalse($societe->timestamps);
    }
}
