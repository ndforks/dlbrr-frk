<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $table = 'llx_asset';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    
    protected $fillable = [
        'ref',
        'label',
        'fk_asset_type',
        'acquisition_value',
        'recovered_vat',
        'reversal_date',
        'date_acquisition',
        'entity',
    ];
}
