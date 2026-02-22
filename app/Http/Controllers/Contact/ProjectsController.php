<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectsController extends Controller
{
    public function __invoke(Request $request, int $id): View
    {
        $contact = Contact::findOrFail($id);
        
        // Get projects for this contact
        // This would typically query projet_contacts or similar table
        $projects = collect(); // Placeholder
        
        return view('contact.projects', [
            'contact' => $contact,
            'projects' => $projects,
        ]);
    }
}
