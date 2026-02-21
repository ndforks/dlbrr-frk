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
        $action = GETPOST('action', 'alpha') ?: 'view';
        $id = GETPOSTINT('id');
        $socid = GETPOSTINT('socid');
        
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
            'nom' => GETPOST('nom', 'alpha'),
            'name_alias' => GETPOST('name_alias', 'alpha'),
            'address' => GETPOST('address', 'alpha'),
            'zip' => GETPOST('zip', 'alpha'),
            'town' => GETPOST('town', 'alpha'),
            'phone' => GETPOST('phone', 'alpha'),
            'email' => GETPOST('email', 'alpha'),
            'url' => GETPOST('url', 'alpha'),
            'fk_pays' => GETPOSTINT('country_id'),
            'client' => GETPOSTINT('client'),
            'fournisseur' => GETPOSTINT('fournisseur'),
        ];
        
        $data = array_filter($data, fn($value) => $value !== null && $value !== '');
        $societe->update($data);
        
        return redirect("/societe/card.php?id={$id}")->with('success', 'Company updated');
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        Societe::findOrFail($id)->delete();
        return redirect('/societe/list.php')->with('success', 'Company deleted');
    }
}
