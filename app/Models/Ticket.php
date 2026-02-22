<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'llx_ticket';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    
    protected $fillable = [
        'fk_soc',
        'ref',
        'subject',
        'message',
        'fk_statut',
        'type_code',
        'category_code',
        'severity_code',
        'datec',
        'entity',
    ];

    // Relationships (alphabetically sorted)
    
    public function societe()
    {
        return $this->belongsTo(Societe::class, 'fk_soc', 'rowid');
    }
}
