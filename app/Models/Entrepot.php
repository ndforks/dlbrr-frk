<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrepot extends Model
{
    protected $table = 'llx_entrepot';
    
    protected $primaryKey = 'rowid';
    
    public $timestamps = false;
    
    protected $fillable = [
        'ref',
        'datec',
        'tms',
        'entity',
        'fk_parent',
        'label',
        'description',
        'statut',
        'lieu',
        'address',
        'zip',
        'town',
        'fk_departement',
        'fk_pays',
        'phone',
        'fax',
        'warehouse_usage',
        'fk_project',
        'model_pdf',
        'import_key',
    ];
    
    protected $casts = [
        'datec' => 'datetime',
        'tms' => 'datetime',
        'entity' => 'integer',
        'fk_parent' => 'integer',
        'statut' => 'integer',
        'fk_departement' => 'integer',
        'fk_pays' => 'integer',
        'warehouse_usage' => 'integer',
        'fk_project' => 'integer',
    ];
    
    /**
     * Get the parent warehouse
     */
    public function parent()
    {
        return $this->belongsTo(Entrepot::class, 'fk_parent', 'rowid');
    }
    
    /**
     * Get child warehouses
     */
    public function children()
    {
        return $this->hasMany(Entrepot::class, 'fk_parent', 'rowid');
    }
}
