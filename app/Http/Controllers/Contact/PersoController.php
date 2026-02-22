<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PersoController extends Controller
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
        
        return view('contact.perso', [
            'contact' => $contact,
            'action' => 'view',
        ]);
    }
    
    private function edit(Request $request, int $id): View
    {
        $contact = Contact::findOrFail($id);
        
        return view('contact.perso', [
            'contact' => $contact,
            'action' => 'edit',
        ]);
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        $contact = Contact::findOrFail($id);
        
        // Update personal information
        $data = [
            'birthday' => GETPOSTINT('birthday'),
            'birthday_alert' => GETPOSTINT('birthday_alert'),
        ];
        
        $data = array_filter($data, fn($value) => $value !== null && $value !== '');
        $contact->update($data);
        
        return redirect()
            ->route('contact.perso', ['id' => $id])
            ->with('success', 'Personal information updated successfully');
    }
}
