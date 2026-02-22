<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrat extends Model
{
    protected $table = 'llx_contrat';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    
    protected $fillable = [
        'fk_soc',
        'ref',
        'ref_customer',
        'ref_supplier',
        'date_contrat',
        'fk_statut',
        'entity',
    ];

    // Relationships (alphabetically sorted)
    
    public function societe()
    {
        return $this->belongsTo(Societe::class, 'fk_soc', 'rowid');
    }
}
