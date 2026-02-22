<?php

namespace App\Http\Controllers\Adherents;

use App\Http\Controllers\Controller;
use App\Models\Adherent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowAdherents extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $action = $request->input('action', 'view');
        $id = $request->integer('id', 0);
        
        return match($action) {
            'create', 'add' => view('adherents.create', ['action' => 'create']),
            'edit' => view('adherents.edit', ['adherent' => Adherent::findOrFail($id), 'action' => 'edit']),
            'update' => $this->update($request, $id),
            'delete' => $this->delete($request, $id),
            default => view('adherents.show', ['adherent' => Adherent::findOrFail($id), 'action' => 'view']),
        };
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        Adherent::findOrFail($id)->update(array_filter(['firstname' => $request->input('firstname'), 'lastname' => $request->input('lastname')], fn($v) => $v));
        return redirect()->route('adherents.show', ['id' => $id])->with('success', 'Member updated');
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        Adherent::findOrFail($id)->delete();
        return redirect()->route('adherents.list')->with('success', 'Member deleted');
    }
}
