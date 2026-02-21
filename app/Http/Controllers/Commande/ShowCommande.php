<?php

namespace App\Http\Controllers\Commande;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowCommande extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $action = GETPOST('action', 'alpha') ?: 'view';
        $id = GETPOSTINT('id');
        
        return match($action) {
            'create', 'add' => $this->create($request),
            'edit' => $this->edit($request, $id),
            'update' => $this->update($request, $id),
            'delete' => $this->delete($request, $id),
            default => $this->show($request, $id),
        };
    }
    
    private function show(Request $request, int $id): View
    {
        $commande = Commande::with('societe')->findOrFail($id);
        return view('commande.show', ['commande' => $commande, 'action' => 'view']);
    }
    
    private function edit(Request $request, int $id): View
    {
        $commande = Commande::with('societe')->findOrFail($id);
        return view('commande.edit', ['commande' => $commande, 'action' => 'edit']);
    }
    
    private function create(Request $request): View
    {
        return view('commande.create', ['action' => 'create']);
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        $commande = Commande::findOrFail($id);
        
        $data = [
            'ref' => GETPOST('ref', 'alpha'),
            'ref_client' => GETPOST('ref_client', 'alpha'),
            'fk_soc' => GETPOSTINT('socid'),
            'date_commande' => GETPOST('date_commande', 'alpha'),
        ];
        
        $data = array_filter($data, fn($value) => $value !== null && $value !== '');
        $commande->update($data);
        
        return redirect("/commande/card.php?id={$id}")->with('success', 'Order updated');
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        Commande::findOrFail($id)->delete();
        return redirect('/commande/list.php')->with('success', 'Order deleted');
    }
}
