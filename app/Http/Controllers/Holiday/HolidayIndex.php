<?php

namespace App\Http\Controllers\Holiday;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class HolidayIndex extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect()->route('holiday.list');
    }
}
