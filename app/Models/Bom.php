<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bom extends Model
{
    protected $table = 'llx_bom_bom';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    
    protected $fillable = [
        'fk_product',
        'ref',
        'label',
        'description',
        'qty',
        'efficiency',
        'fk_bom_child',
        'entity',
    ];
}
