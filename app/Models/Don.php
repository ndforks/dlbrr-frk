<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Don extends Model
{
    protected $table = 'llx_don';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    protected $guarded = [];

    public function societe()
    {
        return $this->belongsTo(Societe::class, 'fk_soc', 'rowid');
    }
}
