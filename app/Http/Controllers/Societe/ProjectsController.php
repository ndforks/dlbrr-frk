<?php

namespace App\Http\Controllers\Societe;

use App\Http\Controllers\Controller;
use App\Models\Societe;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectsController extends Controller
{
    public function __invoke(Request $request, int $id): View
    {
        $societe = Societe::findOrFail($id);
        
        // Get projects for this third party
        $projects = $societe->projets()->orderBy('dateo', 'DESC')->get();
        
        return view('societe.projects', [
            'societe' => $societe,
            'projects' => $projects,
        ]);
    }
}
