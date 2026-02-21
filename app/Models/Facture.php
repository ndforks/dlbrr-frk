<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    protected $table = 'llx_facture';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    protected $guarded = [];

    public function societe()
    {
        return $this->belongsTo(Societe::class, 'fk_soc', 'rowid');
    }
}
