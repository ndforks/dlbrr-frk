<?php

namespace App\Http\Controllers\Compta\Facture;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowFacture extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $action = $request->input('action', 'view');
        $id = $request->integer('id', 0);
        
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
        $facture = Facture::with('societe')->findOrFail($id);
        return view('facture.show', ['facture' => $facture, 'action' => 'view']);
    }
    
    private function edit(Request $request, int $id): View
    {
        $facture = Facture::with('societe')->findOrFail($id);
        return view('facture.edit', ['facture' => $facture, 'action' => 'edit']);
    }
    
    private function create(Request $request): View
    {
        return view('facture.create', ['action' => 'create']);
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        $facture = Facture::findOrFail($id);
        
        $data = [
            'ref' => $request->input('ref'),
            'ref_client' => $request->input('ref_client'),
            'fk_soc' => $request->integer('socid', 0),
            'datef' => $request->input('datef'),
        ];
        
        $data = array_filter($data, fn($value) => $value !== null && $value !== '');
        $facture->update($data);
        
        return redirect("/compta/facture/card.php?id={$id}")->with('success', 'Invoice updated');
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        Facture::findOrFail($id)->delete();
        return redirect('/compta/facture/list.php')->with('success', 'Invoice deleted');
    }
}
