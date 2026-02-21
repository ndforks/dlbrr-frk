<?php

namespace App\Http\Controllers\Don;

use App\Http\Controllers\Controller;
use App\Models\Don;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowDon extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $action = GETPOST('action', 'alpha') ?: 'view';
        $id = GETPOSTINT('id');
        
        return match($action) {
            'create', 'add' => view('don.create', ['action' => 'create']),
            'edit' => view('don.edit', ['don' => Don::with('societe')->findOrFail($id), 'action' => 'edit']),
            'update' => $this->update($request, $id),
            'delete' => $this->delete($request, $id),
            default => view('don.show', ['don' => Don::with('societe')->findOrFail($id), 'action' => 'view']),
        };
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        Don::findOrFail($id)->update(array_filter(['fk_soc' => GETPOSTINT('socid')], fn($v) => $v));
        return redirect("/don/card.php?id={$id}")->with('success', 'Donation updated');
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        Don::findOrFail($id)->delete();
        return redirect('/don/list.php')->with('success', 'Donation deleted');
    }
}
