<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mrp extends Model
{
    protected $table = 'llx_mrp_mo';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    protected $guarded = [];
}
