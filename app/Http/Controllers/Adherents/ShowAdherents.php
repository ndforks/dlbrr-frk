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
        $action = GETPOST('action', 'alpha') ?: 'view';
        $id = GETPOSTINT('id');
        
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
        Adherent::findOrFail($id)->update(array_filter(['firstname' => GETPOST('firstname', 'alpha'), 'lastname' => GETPOST('lastname', 'alpha')], fn($v) => $v));
        return redirect("/adherents/card.php?id={$id}")->with('success', 'Member updated');
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        Adherent::findOrFail($id)->delete();
        return redirect('/adherents/list.php')->with('success', 'Member deleted');
    }
}
