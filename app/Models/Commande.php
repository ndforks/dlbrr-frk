<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $table = 'llx_commande';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    
    protected $fillable = [
        'fk_soc',
        'fk_projet',
        'fk_user_author',
        'fk_user_valid',
        'ref',
        'ref_client',
        'date_commande',
        'date_livraison',
        'fk_statut',
        'total_ht',
        'total_tva',
        'total_ttc',
        'note_private',
        'note_public',
        'entity',
    ];

    // Relationships (alphabetically sorted)
    
    public function commandeDetails()
    {
        return $this->hasMany(CommandeDetail::class, 'fk_commande', 'rowid');
    }

    public function expeditions()
    {
        return $this->hasMany(Expedition::class, 'fk_commande', 'rowid');
    }

    public function factures()
    {
        return $this->belongsToMany(Facture::class, 'llx_element_element', 'fk_source', 'fk_target')
            ->wherePivot('sourcetype', 'commande')
            ->wherePivot('targettype', 'facture');
    }

    public function projet()
    {
        return $this->belongsTo(Projet::class, 'fk_projet', 'rowid');
    }

    public function societe()
    {
        return $this->belongsTo(Societe::class, 'fk_soc', 'rowid');
    }

    public function userAuthor()
    {
        return $this->belongsTo(User::class, 'fk_user_author', 'rowid');
    }

    public function userValid()
    {
        return $this->belongsTo(User::class, 'fk_user_valid', 'rowid');
    }
}
