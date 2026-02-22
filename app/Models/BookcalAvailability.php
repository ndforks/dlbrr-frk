<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookcalAvailability extends Model
{
    protected $table = 'llx_bookcal_availabilities';
    
    protected $primaryKey = 'rowid';
    
    public $timestamps = false;
    
    protected $fillable = [
        'fk_bookcal_calendar',
        'start_date',
        'end_date',
        'duration',
        'status',
        'entity',
    ];
    
    protected $casts = [
        'fk_bookcal_calendar' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'duration' => 'integer',
        'status' => 'integer',
        'entity' => 'integer',
    ];
    
    /**
     * Get the calendar
     */
    public function calendar()
    {
        return $this->belongsTo(Bookcal::class, 'fk_bookcal_calendar', 'rowid');
    }
}
