<?php

namespace App\Http\Controllers\Societe;

use App\Http\Controllers\Controller;
use App\Models\Societe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowSociete extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $action = $request->input('action', 'view');
        $id = $request->integer('id', 0);
        $socid = $request->integer('socid', 0);
        
        return match($action) {
            'create', 'add' => $this->create($request),
            'edit' => $this->edit($request, $id ?: $socid),
            'update' => $this->update($request, $id ?: $socid),
            'delete' => $this->delete($request, $id ?: $socid),
            default => $this->show($request, $id ?: $socid),
        };
    }
    
    private function show(Request $request, int $id): View
    {
        $societe = Societe::findOrFail($id);
        return view('societe.show', ['societe' => $societe, 'action' => 'view']);
    }
    
    private function edit(Request $request, int $id): View
    {
        $societe = Societe::findOrFail($id);
        return view('societe.edit', ['societe' => $societe, 'action' => 'edit']);
    }
    
    private function create(Request $request): View
    {
        return view('societe.create', ['action' => 'create']);
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        $societe = Societe::findOrFail($id);
        
        $data = [
            'nom' => $request->input('nom'),
            'name_alias' => $request->input('name_alias'),
            'address' => $request->input('address'),
            'zip' => $request->input('zip'),
            'town' => $request->input('town'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'url' => $request->input('url'),
            'fk_pays' => $request->integer('country_id', 0),
            'client' => $request->integer('client', 0),
            'fournisseur' => $request->integer('fournisseur', 0),
        ];
        
        $data = array_filter($data, fn($value) => $value !== null && $value !== '');
        $societe->update($data);
        
        return redirect()->route('societe.show', ['id' => $id])->with('success', 'Company updated');
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        Societe::findOrFail($id)->delete();
        return redirect()->route('societe.list')->with('success', 'Company deleted');
    }
}
