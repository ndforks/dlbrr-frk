<?php

namespace Tests\Unit;

use App\Models\Commande;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommandeModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_commande_can_be_created(): void
    {
        $commande = Commande::create([
            'ref' => 'CMD001',
            'entity' => 1,
            'fk_soc' => 1,
            'total_ht' => 1000.00,
            'total_ttc' => 1200.00,
        ]);

        $this->assertInstanceOf(Commande::class, $commande);
        $this->assertEquals('CMD001', $commande->ref);
        $this->assertEquals(1000.00, $commande->total_ht);
    }

    public function test_commande_has_societe_relationship(): void
    {
        $commande = Commande::create([
            'ref' => 'CMD001',
            'entity' => 1,
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $commande->societe());
    }

    public function test_commande_has_projet_relationship(): void
    {
        $commande = Commande::create([
            'ref' => 'CMD001',
            'entity' => 1,
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $commande->projet());
    }

    public function test_commande_has_commande_details_relationship(): void
    {
        $commande = Commande::create([
            'ref' => 'CMD001',
            'entity' => 1,
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $commande->commandeDetails());
    }

    public function test_commande_model_uses_correct_table(): void
    {
        $commande = new Commande();
        $this->assertEquals('llx_commande', $commande->getTable());
    }

    public function test_commande_model_uses_correct_primary_key(): void
    {
        $commande = new Commande();
        $this->assertEquals('rowid', $commande->getKeyName());
    }

    public function test_commande_model_has_timestamps_disabled(): void
    {
        $commande = new Commande();
        $this->assertFalse($commande->timestamps);
    }
}
