<?php

namespace App\Http\Controllers\Contrat;

use App\Http\Controllers\Controller;
use App\Models\Contrat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowContrat extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $action = GETPOST('action', 'alpha') ?: 'view';
        $id = GETPOSTINT('id');
        
        return match($action) {
            'create', 'add' => view('contrat.create', ['action' => 'create']),
            'edit' => view('contrat.edit', ['contrat' => Contrat::with('societe')->findOrFail($id), 'action' => 'edit']),
            'update' => $this->update($request, $id),
            'delete' => $this->delete($request, $id),
            default => view('contrat.show', ['contrat' => Contrat::with('societe')->findOrFail($id), 'action' => 'view']),
        };
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        Contrat::findOrFail($id)->update(array_filter(['ref' => GETPOST('ref', 'alpha')], fn($v) => $v));
        return redirect("/contrat/card.php?id={$id}")->with('success', 'Contract updated');
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        Contrat::findOrFail($id)->delete();
        return redirect('/contrat/list.php')->with('success', 'Contract deleted');
    }
}
