<?php

namespace App\Http\Controllers\Societe;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HasCrudActions;
use App\Models\Societe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowSociete extends Controller
{
    use HasCrudActions;

    public function __invoke(Request $request): View|RedirectResponse
    {
        $action = $request->input('action', 'view');
        $id = $request->integer('id', 0);
        $socid = $request->integer('socid', 0);
        
        return match($action) {
            'create', 'add' => $this->create($request),
            'edit' => $this->edit($request, $id ?: $socid),
            'update' => $this->update($request, $id ?: $socid),
            'delete' => $this->delete($request, $id ?: $socid),
            default => $this->show($request, $id ?: $socid),
        };
    }

    protected function getModelClass(): string
    {
        return Societe::class;
    }

    protected function getViewPrefix(): string
    {
        return 'societe';
    }

    protected function getShowRouteName(): string
    {
        return 'societe.show';
    }

    protected function getListRouteName(): string
    {
        return 'societe.list';
    }

    protected function getUpdateData(Request $request): array
    {
        return [
            'nom' => $request->input('nom'),
            'name_alias' => $request->input('name_alias'),
            'address' => $request->input('address'),
            'zip' => $request->input('zip'),
            'town' => $request->input('town'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'url' => $request->input('url'),
            'fk_pays' => $request->integer('country_id', 0),
            'client' => $request->integer('client', 0),
            'fournisseur' => $request->integer('fournisseur', 0),
        ];
    }
}
