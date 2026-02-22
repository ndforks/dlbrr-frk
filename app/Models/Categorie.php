<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $table = 'llx_categorie';
    
    protected $primaryKey = 'rowid';
    
    public $timestamps = false;
    
    protected $fillable = [
        'fk_parent',
        'label',
        'type',
        'description',
        'color',
        'fk_soc',
        'visible',
        'import_key',
        'entity',
    ];
    
    protected $casts = [
        'fk_parent' => 'integer',
        'type' => 'integer',
        'fk_soc' => 'integer',
        'visible' => 'integer',
        'entity' => 'integer',
    ];
    
    /**
     * Get the parent category
     */
    public function parent()
    {
        return $this->belongsTo(Categorie::class, 'fk_parent', 'rowid');
    }
    
    /**
     * Get child categories
     */
    public function children()
    {
        return $this->hasMany(Categorie::class, 'fk_parent', 'rowid');
    }
    
    /**
     * Get the company/third-party
     */
    public function societe()
    {
        return $this->belongsTo(Societe::class, 'fk_soc', 'rowid');
    }
}
