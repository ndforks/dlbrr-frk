<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    protected $table = 'llx_product_attribute_value';
    
    protected $primaryKey = 'rowid';
    
    public $timestamps = false;
    
    protected $fillable = [
        'fk_product_attribute',
        'ref',
        'value',
        'position',
        'entity',
    ];
    
    protected $casts = [
        'fk_product_attribute' => 'integer',
        'position' => 'integer',
        'entity' => 'integer',
    ];
    
    /**
     * Get the parent attribute
     */
    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'fk_product_attribute', 'rowid');
    }
}
