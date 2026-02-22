<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $table = 'llx_holiday';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    
    protected $fillable = [
        'fk_user',
        'date_debut',
        'date_fin',
        'halfday',
        'fk_type',
        'fk_statut',
        'description',
        'entity',
    ];
}
