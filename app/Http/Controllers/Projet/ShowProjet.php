<?php

namespace App\Http\Controllers\Projet;

use App\Http\Controllers\Controller;
use App\Models\Projet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowProjet extends Controller
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
        $projet = Projet::with('societe')->findOrFail($id);
        return view('projet.show', ['projet' => $projet, 'action' => 'view']);
    }
    
    private function edit(Request $request, int $id): View
    {
        $projet = Projet::with('societe')->findOrFail($id);
        return view('projet.edit', ['projet' => $projet, 'action' => 'edit']);
    }
    
    private function create(Request $request): View
    {
        return view('projet.create', ['action' => 'create']);
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        $projet = Projet::findOrFail($id);
        
        $data = [
            'ref' => $request->input('ref'),
            'title' => $request->input('title'),
            'fk_soc' => $request->integer('socid', 0),
            'description' => $request->input('description'),
        ];
        
        $data = array_filter($data, fn($value) => $value !== null && $value !== '');
        $projet->update($data);
        
        return redirect("/projet/card.php?id={$id}")->with('success', 'Project updated');
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        Projet::findOrFail($id)->delete();
        return redirect('/projet/list.php')->with('success', 'Project deleted');
    }
}
