<?php

namespace App\Http\Controllers\Comm\Propal;

use App\Http\Controllers\Controller;
use App\Models\Propal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowPropal extends Controller
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
        $propal = Propal::with('societe')->findOrFail($id);
        return view('propal.show', ['propal' => $propal, 'action' => 'view']);
    }
    
    private function edit(Request $request, int $id): View
    {
        $propal = Propal::with('societe')->findOrFail($id);
        return view('propal.edit', ['propal' => $propal, 'action' => 'edit']);
    }
    
    private function create(Request $request): View
    {
        return view('propal.create', ['action' => 'create']);
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        $propal = Propal::findOrFail($id);
        $data = ['ref' => $request->input('ref'), 'fk_soc' => $request->integer('socid', 0)];
        $propal->update(array_filter($data, fn($v) => $v !== null && $v !== ''));
        return redirect()->route('propal.show', ['id' => $id])->with('success', 'Proposal updated');
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        Propal::findOrFail($id)->delete();
        return redirect()->route('propal.list')->with('success', 'Proposal deleted');
    }
}
