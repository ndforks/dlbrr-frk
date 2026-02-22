<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $table = 'llx_bookmark';
    
    protected $primaryKey = 'rowid';
    
    public $timestamps = false;
    
    protected $fillable = [
        'fk_user',
        'dateb',
        'url',
        'target',
        'title',
        'favicon',
        'position',
        'entity',
    ];
    
    protected $casts = [
        'fk_user' => 'integer',
        'dateb' => 'datetime',
        'position' => 'integer',
        'entity' => 'integer',
    ];
    
    /**
     * Get the user that owns the bookmark
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'fk_user', 'rowid');
    }
}
