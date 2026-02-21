<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adherent extends Model
{
    protected $table = 'llx_adherent';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    protected $guarded = [];
}
