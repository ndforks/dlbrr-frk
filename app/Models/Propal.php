<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Propal extends Model
{
    protected $table = 'llx_propal';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    protected $guarded = [];

    public function societe()
    {
        return $this->belongsTo(Societe::class, 'fk_soc', 'rowid');
    }
}
