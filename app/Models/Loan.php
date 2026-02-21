<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $table = 'llx_loan';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    protected $guarded = [];
}
