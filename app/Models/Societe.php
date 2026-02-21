<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Societe extends Model
{
    protected $table = 'llx_societe';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    protected $guarded = [];

    // Relationships (alphabetically sorted)
    
    public function adherents()
    {
        return $this->hasMany(Adherent::class, 'fk_soc', 'rowid');
    }

    public function commandesFournisseur()
    {
        return $this->hasMany(CommandeFournisseur::class, 'fk_soc', 'rowid');
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class, 'fk_soc', 'rowid');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'fk_soc', 'rowid');
    }

    public function contrats()
    {
        return $this->hasMany(Contrat::class, 'fk_soc', 'rowid');
    }

    public function expeditions()
    {
        return $this->hasMany(Expedition::class, 'fk_soc', 'rowid');
    }

    public function factures()
    {
        return $this->hasMany(Facture::class, 'fk_soc', 'rowid');
    }

    public function facturesFournisseur()
    {
        return $this->hasMany(FactureFournisseur::class, 'fk_soc', 'rowid');
    }

    public function fichinters()
    {
        return $this->hasMany(Fichinter::class, 'fk_soc', 'rowid');
    }

    public function projets()
    {
        return $this->hasMany(Projet::class, 'fk_soc', 'rowid');
    }

    public function propals()
    {
        return $this->hasMany(Propal::class, 'fk_soc', 'rowid');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'fk_soc', 'rowid');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'fk_soc', 'rowid');
    }
}
