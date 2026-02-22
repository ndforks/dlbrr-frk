<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Propal extends Model
{
    protected $table = 'llx_propal';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    
    protected $fillable = [
        'fk_soc',
        'fk_projet',
        'ref',
        'ref_client',
        'datep',
        'fin_validite',
        'fk_statut',
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
