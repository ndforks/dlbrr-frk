<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $table = 'llx_website';
    
    protected $primaryKey = 'rowid';
    
    public $timestamps = false;
    
    protected $fillable = [
        'entity',
        'ref',
        'description',
        'lang',
        'otherlang',
        'status',
        'fk_default_home',
        'virtualhost',
        'fk_user_creat',
        'fk_user_modif',
        'date_creation',
        'tms',
        'import_key',
        'position',
    ];
    
    protected $casts = [
        'entity' => 'integer',
        'status' => 'integer',
        'fk_default_home' => 'integer',
        'fk_user_creat' => 'integer',
        'fk_user_modif' => 'integer',
        'date_creation' => 'datetime',
        'tms' => 'datetime',
        'position' => 'integer',
    ];
    
    /**
     * Get the pages for this website
     */
    public function pages()
    {
        return $this->hasMany(WebsitePage::class, 'fk_website', 'rowid');
    }
    
    /**
     * Get the default home page
     */
    public function defaultHome()
    {
        return $this->belongsTo(WebsitePage::class, 'fk_default_home', 'rowid');
    }
    
    /**
     * Get the user who created the website
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'fk_user_creat', 'rowid');
    }
}
