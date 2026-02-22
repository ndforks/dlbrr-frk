<?php

namespace App\Http\Controllers\Collab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CollabController extends Controller
{
    public function showIndex(Request $request): View
    {
        global $db, $langs, $user;

        // Security check - Admin only
        if (!$user->admin) {
            abort(403);
        }

        $langs->loadLangs(['admin', 'other', 'website']);

        $action = $request->input('action', 'preview');
        $website = $request->input('website');
        $page = $request->input('page');
        $pageid = $request->integer('pageid', 0);

        // Override action based on request parameters
        if ($request->has('delete')) {
            $action = 'delete';
        } elseif ($request->has('preview')) {
            $action = 'preview';
        } elseif ($request->has('create')) {
            $action = 'create';
        } elseif ($request->has('editmedia')) {
            $action = 'editmedia';
        } elseif ($request->has('editcss')) {
            $action = 'editcss';
        } elseif ($request->has('editmenu')) {
            $action = 'editmenu';
        } elseif ($request->has('setashome')) {
            $action = 'setashome';
        } elseif ($request->has('editmeta')) {
            $action = 'editmeta';
        } elseif ($request->has('editcontent')) {
            $action = 'editcontent';
        }

        return view('collab.index', [
            'action' => $action,
            'website' => $website,
            'page' => $page,
            'pageid' => $pageid,
        ]);
    }
}
