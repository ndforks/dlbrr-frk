<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'llx_product';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    protected $guarded = [];

    // Relationships (alphabetically sorted)
    
    public function boms()
    {
        return $this->hasMany(Bom::class, 'fk_product', 'rowid');
    }

    public function commandeDetails()
    {
        return $this->hasMany(CommandeDetail::class, 'fk_product', 'rowid');
    }

    public function factureDetails()
    {
        return $this->hasMany(FactureDetail::class, 'fk_product', 'rowid');
    }

    public function propalDetails()
    {
        return $this->hasMany(PropalDetail::class, 'fk_product', 'rowid');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'fk_product', 'rowid');
    }
}
