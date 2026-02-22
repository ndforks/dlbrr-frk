<?php

namespace App\Http\Controllers\Societe;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Societe;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactsController extends Controller
{
    public function __invoke(Request $request, int $id): View
    {
        $societe = Societe::findOrFail($id);
        
        // Get contacts for this third party
        $contacts = Contact::where('fk_soc', $societe->id)
            ->orderBy('lastname', 'ASC')
            ->orderBy('firstname', 'ASC')
            ->get();
        
        return view('societe.contacts', [
            'societe' => $societe,
            'contacts' => $contacts,
        ]);
    }
}
