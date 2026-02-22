<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    protected $table = 'llx_facture';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    
    protected $fillable = [
        'fk_soc',
        'ref',
        'ref_client',
        'type',
        'fk_statut',
        'datef',
        'date_lim_reglement',
        'total_ht',
        'total_tva',
        'total_ttc',
        'entity',
    ];

    // Relationships (alphabetically sorted)
    
    public function societe()
    {
        return $this->belongsTo(Societe::class, 'fk_soc', 'rowid');
    }
}
