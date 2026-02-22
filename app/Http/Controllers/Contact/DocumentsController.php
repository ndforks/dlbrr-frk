<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DocumentsController extends Controller
{
    public function __invoke(Request $request, int $id): View
    {
        $contact = Contact::findOrFail($id);
        
        // Get document directory path
        $uploadDir = getDolGlobalString('CONTACT_OUTPUTDIR') ?: 'contact';
        $documentPath = "{$uploadDir}/{$contact->id}";
        
        return view('contact.documents', [
            'contact' => $contact,
            'documentPath' => $documentPath,
        ]);
    }
}
