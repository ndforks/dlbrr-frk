<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgendaController extends Controller
{
    public function __invoke(Request $request, int $id): View
    {
        $contact = Contact::findOrFail($id);
        
        // Get agenda/events for this contact
        // This would typically query actioncomm table
        return view('contact.agenda', [
            'contact' => $contact,
        ]);
    }
}
