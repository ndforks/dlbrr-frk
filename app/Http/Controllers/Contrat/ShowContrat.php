<?php

namespace App\Http\Controllers\Contrat;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HasCrudActions;
use App\Models\Contrat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowContrat extends Controller
{
    use HasCrudActions;

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

    protected function getModelClass(): string
    {
        return Contrat::class;
    }

    protected function getViewPrefix(): string
    {
        return 'contrat';
    }

    protected function getShowRouteName(): string
    {
        return 'contrat.show';
    }

    protected function getListRouteName(): string
    {
        return 'contrat.list';
    }

    protected function loadModel(int $id): Model
    {
        return Contrat::with('societe')->findOrFail($id);
    }

    protected function getUpdateData(Request $request): array
    {
        return [
            'ref' => $request->input('ref'),
        ];
    }
}
