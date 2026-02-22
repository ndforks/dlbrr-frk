<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adherent extends Model
{
    protected $table = 'llx_adherent';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    
    protected $fillable = [
        'fk_soc',
        'civility',
        'firstname',
        'lastname',
        'login',
        'email',
        'phone',
        'phone_mobile',
        'address',
        'zip',
        'town',
        'fk_pays',
        'fk_adherent_type',
        'datefin',
        'entity',
    ];
}
