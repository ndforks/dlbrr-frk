<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowAsset extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $action = GETPOST('action', 'alpha') ?: 'view';
        $id = GETPOSTINT('id');
        
        return match($action) {
            'create', 'add' => view('asset.create', ['action' => 'create']),
            'edit' => view('asset.edit', ['asset' => Asset::findOrFail($id), 'action' => 'edit']),
            'update' => $this->update($request, $id),
            'delete' => $this->delete($request, $id),
            default => view('asset.show', ['asset' => Asset::findOrFail($id), 'action' => 'view']),
        };
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        Asset::findOrFail($id)->update(array_filter(['ref' => GETPOST('ref', 'alpha')], fn($v) => $v));
        return redirect("/asset/card.php?id={$id}")->with('success', 'Asset updated');
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        Asset::findOrFail($id)->delete();
        return redirect('/asset/list.php')->with('success', 'Asset deleted');
    }
}
