<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrmPosition extends Model
{
    protected $table = 'llx_hrm_job';
    
    protected $primaryKey = 'rowid';
    
    public $timestamps = false;
    
    protected $fillable = [
        'entity',
        'ref',
        'label',
        'description',
        'note_public',
        'note_private',
        'date_creation',
        'tms',
        'fk_user_creat',
        'fk_user_modif',
        'import_key',
        'model_pdf',
        'status',
    ];
    
    protected $casts = [
        'entity' => 'integer',
        'date_creation' => 'datetime',
        'tms' => 'datetime',
        'fk_user_creat' => 'integer',
        'fk_user_modif' => 'integer',
        'status' => 'integer',
    ];
    
    /**
     * Get the user who created the position
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'fk_user_creat', 'rowid');
    }
}
