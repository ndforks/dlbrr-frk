<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Don extends Model
{
    protected $table = 'llx_don';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    
    protected $fillable = [
        'fk_soc',
        'ref',
        'amount',
        'datedon',
        'fk_statut',
        'note_private',
        'note_public',
        'entity',
    ];

    // Relationships (alphabetically sorted)
    
    public function societe()
    {
        return $this->belongsTo(Societe::class, 'fk_soc', 'rowid');
    }
}
