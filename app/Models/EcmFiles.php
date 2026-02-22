<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcmFiles extends Model
{
    protected $table = 'llx_ecm_files';
    
    protected $primaryKey = 'rowid';
    
    public $timestamps = false;
    
    protected $fillable = [
        'ref',
        'label',
        'share',
        'share_pass',
        'entity',
        'filepath',
        'filename',
        'src_object_type',
        'src_object_id',
        'fullpath_orig',
        'description',
        'keywords',
        'cover',
        'position',
        'gen_or_uploaded',
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
        'src_object_id' => 'integer',
        'position' => 'integer',
        'date_c' => 'datetime',
        'tms' => 'datetime',
        'fk_user_c' => 'integer',
        'fk_user_m' => 'integer',
    ];
    
    /**
     * Get the user who created the file
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'fk_user_c', 'rowid');
    }
    
    /**
     * Get the directory
     */
    public function directory()
    {
        return $this->belongsTo(EcmDirectory::class, 'filepath', 'fullrelativename');
    }
}
