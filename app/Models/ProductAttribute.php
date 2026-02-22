<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    protected $table = 'llx_product_attribute';
    
    protected $primaryKey = 'rowid';
    
    public $timestamps = false;
    
    protected $fillable = [
        'ref',
        'ref_ext',
        'label',
        'position',
        'entity',
    ];
    
    protected $casts = [
        'position' => 'integer',
        'entity' => 'integer',
    ];
    
    /**
     * Get the attribute values
     */
    public function values()
    {
        return $this->hasMany(ProductAttributeValue::class, 'fk_product_attribute', 'rowid');
    }
}
