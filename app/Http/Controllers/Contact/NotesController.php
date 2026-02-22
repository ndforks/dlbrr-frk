<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotesController extends Controller
{
    public function __invoke(Request $request, int $id): View|RedirectResponse
    {
        $action = GETPOST('action', 'alpha') ?: 'view';
        
        return match($action) {
            'edit' => $this->edit($request, $id),
            'update' => $this->update($request, $id),
            default => $this->show($request, $id),
        };
    }
    
    private function show(Request $request, int $id): View
    {
        $contact = Contact::findOrFail($id);
        
        return view('contact.notes', [
            'contact' => $contact,
            'action' => 'view',
        ]);
    }
    
    private function edit(Request $request, int $id): View
    {
        $contact = Contact::findOrFail($id);
        
        return view('contact.notes', [
            'contact' => $contact,
            'action' => 'edit',
        ]);
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        $contact = Contact::findOrFail($id);
        
        $notePublic = GETPOST('note_public', 'restricthtml');
        $notePrivate = GETPOST('note_private', 'restricthtml');
        
        if ($notePublic !== null) {
            $contact->note_public = $notePublic;
        }
        if ($notePrivate !== null) {
            $contact->note_private = $notePrivate;
        }
        
        $contact->save();
        
        return redirect()
            ->route('contact.notes', ['id' => $id])
            ->with('success', 'Notes updated successfully');
    }
}
