<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookcal extends Model
{
    protected $table = 'llx_bookcal_calendar';
    
    protected $primaryKey = 'rowid';
    
    public $timestamps = false;
    
    protected $fillable = [
        'entity',
        'ref',
        'label',
        'status',
        'fk_soc',
        'fk_project',
        'description',
        'note_public',
        'note_private',
        'date_creation',
        'tms',
        'fk_user_creat',
        'fk_user_modif',
        'import_key',
        'model_pdf',
        'extraparams',
    ];
    
    protected $casts = [
        'entity' => 'integer',
        'status' => 'integer',
        'fk_soc' => 'integer',
        'fk_project' => 'integer',
        'date_creation' => 'datetime',
        'tms' => 'datetime',
        'fk_user_creat' => 'integer',
        'fk_user_modif' => 'integer',
    ];
    
    /**
     * Get the company/third-party
     */
    public function societe()
    {
        return $this->belongsTo(Societe::class, 'fk_soc', 'rowid');
    }
    
    /**
     * Get the project
     */
    public function project()
    {
        return $this->belongsTo(Projet::class, 'fk_project', 'rowid');
    }
    
    /**
     * Get the creator user
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'fk_user_creat', 'rowid');
    }
    
    /**
     * Get the availabilities for this calendar
     */
    public function availabilities()
    {
        return $this->hasMany(BookcalAvailability::class, 'fk_bookcal_calendar', 'rowid');
    }
}
