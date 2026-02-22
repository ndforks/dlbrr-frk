<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConferenceOrBooth extends Model
{
    protected $table = 'llx_actioncomm';
    
    protected $primaryKey = 'id';
    
    public $timestamps = false;
    
    protected $fillable = [
        'ref',
        'ref_ext',
        'entity',
        'datep',
        'datep2',
        'fk_action',
        'code',
        'datec',
        'tms',
        'fk_user_author',
        'fk_user_mod',
        'fk_project',
        'fk_soc',
        'fk_contact',
        'fk_parent',
        'fk_user_action',
        'fk_user_done',
        'priority',
        'fulldayevent',
        'punctual',
        'percent',
        'location',
        'durationp',
        'label',
        'note',
        'calling_duration',
        'email_subject',
        'email_msgid',
        'email_from',
        'email_sender',
        'email_to',
        'email_tocc',
        'email_tobcc',
        'errors_to',
        'recurid',
        'recurrule',
        'recurdateend',
        'import_key',
        'extraparams',
    ];
    
    protected $casts = [
        'entity' => 'integer',
        'datep' => 'datetime',
        'datep2' => 'datetime',
        'fk_action' => 'integer',
        'datec' => 'datetime',
        'tms' => 'datetime',
        'fk_user_author' => 'integer',
        'fk_user_mod' => 'integer',
        'fk_project' => 'integer',
        'fk_soc' => 'integer',
        'fk_contact' => 'integer',
        'fk_parent' => 'integer',
        'fk_user_action' => 'integer',
        'fk_user_done' => 'integer',
        'priority' => 'integer',
        'fulldayevent' => 'integer',
        'punctual' => 'integer',
        'percent' => 'integer',
        'durationp' => 'float',
        'recurdateend' => 'datetime',
    ];
    
    /**
     * Get the project
     */
    public function project()
    {
        return $this->belongsTo(Projet::class, 'fk_project', 'rowid');
    }
    
    /**
     * Get the company/third-party
     */
    public function societe()
    {
        return $this->belongsTo(Societe::class, 'fk_soc', 'rowid');
    }
    
    /**
     * Get the contact
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'fk_contact', 'rowid');
    }
}
