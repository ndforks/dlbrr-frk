<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class PositionHrm extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/hrm/position.php');
    }
}