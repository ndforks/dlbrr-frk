<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $table = 'llx_asset';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    protected $guarded = [];
}
