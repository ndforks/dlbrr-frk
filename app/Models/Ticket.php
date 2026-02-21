<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'llx_ticket';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    protected $guarded = [];

    public function societe()
    {
        return $this->belongsTo(Societe::class, 'fk_soc', 'rowid');
    }
}
