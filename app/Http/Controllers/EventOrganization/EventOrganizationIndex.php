<?php

namespace App\Http\Controllers\EventOrganization;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class EventOrganizationIndex extends Controller
{
    /**
     * Handle the incoming request.
     * Display EventOrganization home page.
     */
    public function __invoke(): View
    {
        global $langs, $user;
        
        // Load translation files
        $langs->loadLangs(['eventorganization']);
        
        // Security check
        if ($user->socid > 0) {
            accessforbidden();
        }
        restrictedArea($user, 'eventorganization');
        
        $title = $langs->trans('EventOrganizationArea');
        
        return view('eventorganization.index', [
            'title' => $title,
            'helpUrl' => 'EN:Module_Event_Organization',
        ]);
    }
}
