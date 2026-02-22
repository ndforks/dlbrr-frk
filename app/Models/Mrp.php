<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mrp extends Model
{
    protected $table = 'llx_mrp_mo';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    
    protected $fillable = [
        'ref',
        'label',
        'fk_bom',
        'fk_product',
        'qty',
        'fk_warehouse',
        'fk_status',
        'date_start_planned',
        'date_end_planned',
        'entity',
    ];
}
