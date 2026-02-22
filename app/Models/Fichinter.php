<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fichinter extends Model
{
    protected $table = 'llx_fichinter';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    
    protected $fillable = [
        'fk_soc',
        'ref',
        'description',
        'datec',
        'fk_statut',
        'duree',
        'entity',
    ];

    // Relationships (alphabetically sorted)
    
    public function societe()
    {
        return $this->belongsTo(Societe::class, 'fk_soc', 'rowid');
    }
}
