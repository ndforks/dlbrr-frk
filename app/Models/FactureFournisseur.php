<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FactureFournisseur extends Model
{
    protected $table = 'llx_facture_fourn';
    
    protected $primaryKey = 'rowid';
    
    public $timestamps = false;
    
    protected $fillable = [
        'ref',
        'ref_supplier',
        'entity',
        'ref_ext',
        'type',
        'fk_soc',
        'datec',
        'datef',
        'date_pointoftax',
        'date_valid',
        'tms',
        'libelle',
        'paye',
        'amount',
        'remise',
        'close_code',
        'close_note',
        'tva',
        'localtax1',
        'localtax2',
        'total_ht',
        'total_tva',
        'total_ttc',
        'revenuestamp',
        'fk_statut',
        'fk_user_author',
        'fk_user_modif',
        'fk_user_valid',
        'fk_facture_source',
        'fk_projet',
        'fk_account',
        'fk_cond_reglement',
        'fk_mode_reglement',
        'date_lim_reglement',
        'note_private',
        'note_public',
        'model_pdf',
        'import_key',
        'extraparams',
        'fk_multicurrency',
        'multicurrency_code',
        'multicurrency_tx',
        'multicurrency_total_ht',
        'multicurrency_total_tva',
        'multicurrency_total_ttc',
    ];
    
    protected $casts = [
        'entity' => 'integer',
        'type' => 'integer',
        'fk_soc' => 'integer',
        'datec' => 'datetime',
        'datef' => 'date',
        'date_pointoftax' => 'date',
        'date_valid' => 'datetime',
        'tms' => 'datetime',
        'paye' => 'integer',
        'amount' => 'float',
        'remise' => 'float',
        'tva' => 'float',
        'localtax1' => 'float',
        'localtax2' => 'float',
        'total_ht' => 'float',
        'total_tva' => 'float',
        'total_ttc' => 'float',
        'revenuestamp' => 'float',
        'fk_statut' => 'integer',
        'fk_user_author' => 'integer',
        'date_lim_reglement' => 'date',
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
