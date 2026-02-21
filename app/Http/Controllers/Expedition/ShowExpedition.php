<?php

namespace App\Http\Controllers\Expedition;

use App\Http\Controllers\Controller;
use App\Models\Expedition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowExpedition extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $action = GETPOST('action', 'alpha') ?: 'view';
        $id = GETPOSTINT('id');
        
        return match($action) {
            'create', 'add' => view('expedition.create', ['action' => 'create']),
            'edit' => view('expedition.edit', ['expedition' => Expedition::with('societe')->findOrFail($id), 'action' => 'edit']),
            'update' => $this->update($request, $id),
            'delete' => $this->delete($request, $id),
            default => view('expedition.show', ['expedition' => Expedition::with('societe')->findOrFail($id), 'action' => 'view']),
        };
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        Expedition::findOrFail($id)->update(array_filter(['ref' => GETPOST('ref', 'alpha')], fn($v) => $v));
        return redirect("/expedition/card.php?id={$id}")->with('success', 'Shipment updated');
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        Expedition::findOrFail($id)->delete();
        return redirect('/expedition/list.php')->with('success', 'Shipment deleted');
    }
}
