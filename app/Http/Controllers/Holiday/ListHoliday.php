<?php

namespace App\Http\Controllers\Holiday;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ListHoliday extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/holiday/list.php');
    }
}