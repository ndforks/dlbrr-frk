<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommandeFournisseur extends Model
{
    protected $table = 'llx_commande_fournisseur';
    
    protected $primaryKey = 'rowid';
    
    public $timestamps = false;
    
    protected $fillable = [
        'ref',
        'ref_supplier',
        'entity',
        'ref_ext',
        'fk_soc',
        'fk_projet',
        'tms',
        'date_creation',
        'date_valid',
        'date_approve',
        'date_approve2',
        'date_commande',
        'fk_user_author',
        'fk_user_modif',
        'fk_user_valid',
        'fk_user_approve',
        'fk_user_approve2',
        'source',
        'fk_statut',
        'billed',
        'amount_ht',
        'remise_percent',
        'remise',
        'total_ht',
        'total_tva',
        'localtax1',
        'localtax2',
        'total_ttc',
        'note_private',
        'note_public',
        'model_pdf',
        'date_livraison',
        'fk_account',
        'fk_cond_reglement',
        'fk_mode_reglement',
        'fk_input_reason',
        'fk_multicurrency',
        'multicurrency_code',
        'multicurrency_tx',
        'multicurrency_total_ht',
        'multicurrency_total_tva',
        'multicurrency_total_ttc',
        'import_key',
        'extraparams',
    ];
    
    protected $casts = [
        'entity' => 'integer',
        'fk_soc' => 'integer',
        'fk_projet' => 'integer',
        'tms' => 'datetime',
        'date_creation' => 'datetime',
        'date_valid' => 'datetime',
        'date_approve' => 'datetime',
        'date_approve2' => 'datetime',
        'date_commande' => 'date',
        'fk_user_author' => 'integer',
        'fk_user_modif' => 'integer',
        'fk_statut' => 'integer',
        'billed' => 'integer',
        'amount_ht' => 'float',
        'remise_percent' => 'float',
        'remise' => 'float',
        'total_ht' => 'float',
        'total_tva' => 'float',
        'total_ttc' => 'float',
        'date_livraison' => 'date',
    ];
    
    /**
     * Get the supplier
     */
    public function societe()
    {
        return $this->belongsTo(Societe::class, 'fk_soc', 'rowid');
    }
    
    /**
     * Get the project
     */
    public function project()
    {
        return $this->belongsTo(Projet::class, 'fk_projet', 'rowid');
    }
    
    /**
     * Get the author
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'fk_user_author', 'rowid');
    }
}
