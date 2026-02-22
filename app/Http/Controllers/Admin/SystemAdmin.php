<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SystemAdmin extends Controller
{
    public function __invoke(): View
    {
        global $conf, $user, $langs, $db;
        
        $langs->loadLangs(['install', 'other', 'admin']);
        
        if (!$user->admin) {
            accessforbidden();
        }
        
        return view('admin.system', [
            'conf' => $conf,
            'langs' => $langs,
            'db' => $db,
        ]);
    }
}
