<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsitePage extends Model
{
    protected $table = 'llx_website_page';
    
    protected $primaryKey = 'rowid';
    
    public $timestamps = false;
    
    protected $fillable = [
        'fk_website',
        'pageurl',
        'aliasalt',
        'title',
        'description',
        'image',
        'keywords',
        'lang',
        'fk_page',
        'allowed_in_frames',
        'htmlheader',
        'content',
        'status',
        'grabbed_from',
        'type_container',
        'fk_user_creat',
        'fk_user_modif',
        'author_alias',
        'date_creation',
        'tms',
        'import_key',
    ];
    
    protected $casts = [
        'fk_website' => 'integer',
        'fk_page' => 'integer',
        'allowed_in_frames' => 'integer',
        'status' => 'integer',
        'fk_user_creat' => 'integer',
        'fk_user_modif' => 'integer',
        'date_creation' => 'datetime',
        'tms' => 'datetime',
    ];
    
    /**
     * Get the website that owns the page
     */
    public function website()
    {
        return $this->belongsTo(Website::class, 'fk_website', 'rowid');
    }
    
    /**
     * Get the user who created the page
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'fk_user_creat', 'rowid');
    }
}
