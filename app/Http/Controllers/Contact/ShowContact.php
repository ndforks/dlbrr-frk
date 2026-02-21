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
        $action = GETPOST('action', 'alpha') ?: 'view';
        $id = GETPOSTINT('id');
        $socid = GETPOSTINT('socid');
        
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
        
        // Get form data using helper
        $data = [
            'lastname' => GETPOST('lastname', 'alpha'),
            'firstname' => GETPOST('firstname', 'alpha'),
            'email' => GETPOST('email', 'alpha'),
            'phone' => GETPOST('phone', 'alpha'),
            'phone_mobile' => GETPOST('phone_mobile', 'alpha'),
            'phone_perso' => GETPOST('phone_perso', 'alpha'),
            'fax' => GETPOST('fax', 'alpha'),
            'poste' => GETPOST('poste', 'alpha'),
            'address' => GETPOST('address', 'alpha'),
            'zip' => GETPOST('zip', 'alpha'),
            'town' => GETPOST('town', 'alpha'),
            'fk_soc' => GETPOSTINT('socid'),
            'fk_pays' => GETPOSTINT('country_id'),
            'priv' => GETPOSTINT('priv'),
            'note_public' => GETPOST('note_public', 'restricthtml'),
            'note_private' => GETPOST('note_private', 'restricthtml'),
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
