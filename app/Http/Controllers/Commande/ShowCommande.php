<?php

namespace App\Http\Controllers\Commande;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HasCrudActions;
use App\Models\Commande;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowCommande extends Controller
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
        return Commande::class;
    }

    protected function getViewPrefix(): string
    {
        return 'commande';
    }

    protected function getShowRouteName(): string
    {
        return 'commande.show';
    }

    protected function getListRouteName(): string
    {
        return 'commande.list';
    }

    protected function loadModel(int $id): Model
    {
        return Commande::with('societe')->findOrFail($id);
    }

    protected function getUpdateData(Request $request): array
    {
        return [
            'ref' => $request->input('ref'),
            'ref_client' => $request->input('ref_client'),
            'fk_soc' => $request->integer('socid', 0),
            'date_commande' => $request->input('date_commande'),
        ];
    }
}
