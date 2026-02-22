<?php

namespace App\Http\Controllers\Societe;

use App\Http\Controllers\Controller;
use App\Models\Societe;
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
        $societe = Societe::findOrFail($id);
        
        return view('societe.notes', [
            'societe' => $societe,
            'action' => 'view',
        ]);
    }
    
    private function edit(Request $request, int $id): View
    {
        $societe = Societe::findOrFail($id);
        
        return view('societe.notes', [
            'societe' => $societe,
            'action' => 'edit',
        ]);
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        $societe = Societe::findOrFail($id);
        
        $notePublic = GETPOST('note_public', 'restricthtml');
        $notePrivate = GETPOST('note_private', 'restricthtml');
        
        if ($notePublic !== null) {
            $societe->note_public = $notePublic;
        }
        if ($notePrivate !== null) {
            $societe->note_private = $notePrivate;
        }
        
        $societe->save();
        
        return redirect()
            ->route('societe.notes', ['id' => $id])
            ->with('success', 'Notes updated successfully');
    }
}
