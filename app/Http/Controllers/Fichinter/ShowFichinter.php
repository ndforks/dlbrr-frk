<?php

namespace App\Http\Controllers\Fichinter;

use App\Http\Controllers\Controller;
use App\Models\Fichinter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowFichinter extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $action = $request->input('action', 'view');
        $id = $request->integer('id', 0);
        
        return match($action) {
            'create', 'add' => view('fichinter.create', ['action' => 'create']),
            'edit' => view('fichinter.edit', ['fichinter' => Fichinter::with('societe')->findOrFail($id), 'action' => 'edit']),
            'update' => $this->update($request, $id),
            'delete' => $this->delete($request, $id),
            default => view('fichinter.show', ['fichinter' => Fichinter::with('societe')->findOrFail($id), 'action' => 'view']),
        };
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        Fichinter::findOrFail($id)->update(array_filter(['ref' => $request->input('ref')], fn($v) => $v));
        return redirect("/fichinter/card.php?id={$id}")->with('success', 'Intervention updated');
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        Fichinter::findOrFail($id)->delete();
        return redirect('/fichinter/list.php')->with('success', 'Intervention deleted');
    }
}
