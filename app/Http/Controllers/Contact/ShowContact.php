<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Societe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowContact extends Controller
{
    /**
     * Handle the incoming request.
     * Displays, edits, creates, or updates a contact.
     */
    public function __invoke(Request $request): View|RedirectResponse
    {
        $action = $request->input('action', 'view');
        $id = $request->integer('id', 0);
        $socid = $request->integer('socid', 0);
        
        // Handle different actions
        return match($action) {
            'create', 'add' => $this->create($request, $socid),
            'edit' => $this->edit($request, $id),
            'update' => $this->update($request, $id),
            'delete' => $this->delete($request, $id),
            default => $this->show($request, $id),
        };
    }
    
    /**
     * Show contact details
     */
    private function show(Request $request, int $id): View
    {
        $contact = Contact::with('societe')->findOrFail($id);
        
        return view('contact.show', [
            'contact' => $contact,
            'action' => 'view',
        ]);
    }
    
    /**
     * Show edit form
     */
    private function edit(Request $request, int $id): View
    {
        $contact = Contact::with('societe')->findOrFail($id);
        $societes = Societe::orderBy('nom')->get();
        
        return view('contact.edit', [
            'contact' => $contact,
            'societes' => $societes,
            'action' => 'edit',
        ]);
    }
    
    /**
     * Show create form
     */
    private function create(Request $request, ?int $socid = null): View
    {
        $societes = Societe::orderBy('nom')->get();
        $selectedSociete = $socid ? Societe::find($socid) : null;
        
        return view('contact.create', [
            'societes' => $societes,
            'selectedSociete' => $selectedSociete,
            'action' => 'create',
        ]);
    }
    
    /**
     * Update existing contact
     */
    private function update(Request $request, int $id): RedirectResponse
    {
        $contact = Contact::findOrFail($id);
        
        // Get form data using Laravel request
        $data = [
            'lastname' => $request->input('lastname'),
            'firstname' => $request->input('firstname'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'phone_mobile' => $request->input('phone_mobile'),
            'phone_perso' => $request->input('phone_perso'),
            'fax' => $request->input('fax'),
            'poste' => $request->input('poste'),
            'address' => $request->input('address'),
            'zip' => $request->input('zip'),
            'town' => $request->input('town'),
            'fk_soc' => $request->integer('socid', 0),
            'fk_pays' => $request->integer('country_id', 0),
            'priv' => $request->integer('priv', 0),
            'note_public' => $request->input('note_public'),
            'note_private' => $request->input('note_private'),
        ];
        
        // Remove null values
        $data = array_filter($data, fn($value) => $value !== null && $value !== '');
        
        $contact->update($data);
        
        return redirect("/contact/card.php?id={$id}")
            ->with('success', 'Contact updated successfully');
    }
    
    /**
     * Delete contact
     */
    private function delete(Request $request, int $id): RedirectResponse
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        
        return redirect('/contact/list.php')
            ->with('success', 'Contact deleted successfully');
    }
}
