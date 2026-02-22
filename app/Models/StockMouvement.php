<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMouvement extends Model
{
    protected $table = 'llx_stock_mouvement';
    
    protected $primaryKey = 'rowid';
    
    public $timestamps = false;
    
    protected $fillable = [
        'tms',
        'datem',
        'fk_product',
        'batch',
        'eatby',
        'sellby',
        'fk_entrepot',
        'value',
        'price',
        'type_mouvement',
        'fk_user_author',
        'label',
        'inventorycode',
        'fk_project',
        'fk_origin',
        'origintype',
        'model_pdf',
        'fk_projet',
    ];
    
    protected $casts = [
        'tms' => 'datetime',
        'datem' => 'datetime',
        'fk_product' => 'integer',
        'eatby' => 'date',
        'sellby' => 'date',
        'fk_entrepot' => 'integer',
        'value' => 'float',
        'price' => 'float',
        'type_mouvement' => 'integer',
        'fk_user_author' => 'integer',
        'fk_project' => 'integer',
        'fk_origin' => 'integer',
        'fk_projet' => 'integer',
    ];
    
    /**
     * Get the product
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'fk_product', 'rowid');
    }
    
    /**
     * Get the warehouse
     */
    public function entrepot()
    {
        return $this->belongsTo(Entrepot::class, 'fk_entrepot', 'rowid');
    }
    
    /**
     * Get the user who created the movement
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'fk_user_author', 'rowid');
    }
}
