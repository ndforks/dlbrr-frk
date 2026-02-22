<?php

namespace App\Http\Controllers\Projet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminIndex extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        if (!$user || !$user->admin) {
            abort(403);
        }

        return view('projet.admin.index', [
            'modulepart' => 'project',
        ]);
    }
}
