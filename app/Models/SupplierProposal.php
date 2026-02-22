<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierProposal extends Model
{
    protected $table = 'llx_supplier_proposal';
    
    protected $primaryKey = 'rowid';
    
    public $timestamps = false;
    
    protected $fillable = [
        'fk_soc',
        'fk_projet',
        'ref',
        'ref_ext',
        'entity',
        'tms',
        'fk_user_author',
        'fk_user_modif',
        'fk_user_valid',
        'fk_user_cloture',
        'fk_statut',
        'price',
        'remise_percent',
        'remise_absolue',
        'remise',
        'total_ht',
        'total_tva',
        'localtax1',
        'localtax2',
        'total_ttc',
        'fk_account',
        'fk_currency',
        'fk_cond_reglement',
        'fk_mode_reglement',
        'note_private',
        'note_public',
        'model_pdf',
        'date_livraison',
        'fk_shipping_method',
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
        'fk_soc' => 'integer',
        'fk_projet' => 'integer',
        'entity' => 'integer',
        'tms' => 'datetime',
        'fk_user_author' => 'integer',
        'fk_user_modif' => 'integer',
        'fk_user_valid' => 'integer',
        'fk_user_cloture' => 'integer',
        'fk_statut' => 'integer',
        'price' => 'float',
        'remise_percent' => 'float',
        'remise_absolue' => 'float',
        'remise' => 'float',
        'total_ht' => 'float',
        'total_tva' => 'float',
        'localtax1' => 'float',
        'localtax2' => 'float',
        'total_ttc' => 'float',
        'date_livraison' => 'datetime',
    ];
    
    /**
     * Get the supplier/third-party
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
     * Get the author user
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'fk_user_author', 'rowid');
    }
}
