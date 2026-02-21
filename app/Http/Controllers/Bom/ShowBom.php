<?php

namespace App\Http\Controllers\Bom;

use App\Http\Controllers\Controller;
use App\Models\Bom;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowBom extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $action = GETPOST('action', 'alpha') ?: 'view';
        $id = GETPOSTINT('id');
        
        return match($action) {
            'create', 'add' => view('bom.create', ['action' => 'create']),
            'edit' => view('bom.edit', ['bom' => Bom::findOrFail($id), 'action' => 'edit']),
            'update' => $this->update($request, $id),
            'delete' => $this->delete($request, $id),
            default => view('bom.show', ['bom' => Bom::findOrFail($id), 'action' => 'view']),
        };
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        Bom::findOrFail($id)->update(array_filter(['ref' => GETPOST('ref', 'alpha')], fn($v) => $v));
        return redirect("/bom/card.php?id={$id}")->with('success', 'BOM updated');
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        Bom::findOrFail($id)->delete();
        return redirect('/bom/list.php')->with('success', 'BOM deleted');
    }
}
