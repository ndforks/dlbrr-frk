<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'llx_socpeople';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    protected $guarded = [];

    // Relationships
    
    public function societe()
    {
        return $this->belongsTo(Societe::class, 'fk_soc', 'rowid');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'fk_user_creat', 'rowid');
    }
}
