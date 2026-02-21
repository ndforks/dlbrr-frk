<?php

namespace App\Http\Controllers\Mrp;

use App\Http\Controllers\Controller;
use App\Models\Mrp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowManufacturingOrder extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $action = GETPOST('action', 'alpha') ?: 'view';
        $id = GETPOSTINT('id');
        
        return match($action) {
            'create', 'add' => view('mrp.create', ['action' => 'create']),
            'edit' => view('mrp.edit', ['mrp' => Mrp::findOrFail($id), 'action' => 'edit']),
            'update' => $this->update($request, $id),
            'delete' => $this->delete($request, $id),
            default => view('mrp.show', ['mrp' => Mrp::findOrFail($id), 'action' => 'view']),
        };
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        Mrp::findOrFail($id)->update(array_filter(['ref' => GETPOST('ref', 'alpha')], fn($v) => $v));
        return redirect("/mrp/mo_card.php?id={$id}")->with('success', 'Manufacturing order updated');
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        Mrp::findOrFail($id)->delete();
        return redirect('/mrp/mo_list.php')->with('success', 'Manufacturing order deleted');
    }
}
