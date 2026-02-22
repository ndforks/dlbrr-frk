<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeHrm extends Controller
{
    /**
     * Handle the incoming request - Employee management
     * Redirects to user list filtered for employees
     */
    public function __invoke(Request $request): RedirectResponse
    {
        global $user;
        
        if (!$user->hasRight('hrm', 'employee', 'read')) {
            accessforbidden();
        }
        
        return redirect()->route('user.list');
    }
}

