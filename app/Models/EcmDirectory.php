<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcmDirectory extends Model
{
    protected $table = 'llx_ecm_directories';
    
    protected $primaryKey = 'rowid';
    
    public $timestamps = false;
    
    protected $fillable = [
        'label',
        'entity',
        'fk_parent',
        'description',
        'cachenbofdoc',
        'fullrelativename',
        'extraparams',
        'date_c',
        'tms',
        'fk_user_c',
        'fk_user_m',
        'note_private',
        'note_public',
        'acl',
    ];
    
    protected $casts = [
        'entity' => 'integer',
        'fk_parent' => 'integer',
        'cachenbofdoc' => 'integer',
        'date_c' => 'datetime',
        'tms' => 'datetime',
        'fk_user_c' => 'integer',
        'fk_user_m' => 'integer',
    ];
    
    /**
     * Get the parent directory
     */
    public function parent()
    {
        return $this->belongsTo(EcmDirectory::class, 'fk_parent', 'rowid');
    }
    
    /**
     * Get child directories
     */
    public function children()
    {
        return $this->hasMany(EcmDirectory::class, 'fk_parent', 'rowid');
    }
    
    /**
     * Get the files in this directory
     */
    public function files()
    {
        return $this->hasMany(EcmFiles::class, 'filepath', 'fullrelativename');
    }
}
