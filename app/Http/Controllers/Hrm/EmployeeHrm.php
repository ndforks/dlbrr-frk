<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class EmployeeHrm extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/hrm/employee.php');
    }
}