<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fichinter extends Model
{
    protected $table = 'llx_fichinter';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    protected $guarded = [];

    public function societe()
    {
        return $this->belongsTo(Societe::class, 'fk_soc', 'rowid');
    }
}
