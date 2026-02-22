<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseReport extends Model
{
    protected $table = 'llx_expensereport';
    protected $primaryKey = 'rowid';
    public $timestamps = false;
    protected $guarded = [];
}
