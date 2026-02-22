<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Configuration constant model
 * Represents entries in the llx_const table
 */
class Const extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'llx_const';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'rowid';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'entity',
        'value',
        'type',
        'visible',
        'note',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'entity' => 'integer',
        'visible' => 'integer',
    ];
}
