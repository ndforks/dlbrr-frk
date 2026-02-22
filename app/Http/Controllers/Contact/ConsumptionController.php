<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsumptionController extends Controller
{
    public function __invoke(Request $request, int $id): View
    {
        $contact = Contact::findOrFail($id);
        
        return view('contact.consumption', [
            'contact' => $contact,
        ]);
    }
}
