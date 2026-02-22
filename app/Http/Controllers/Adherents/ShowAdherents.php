<?php

namespace App\Http\Controllers\Adherents;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HasCrudActions;
use App\Models\Adherent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowAdherents extends Controller
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
        return Adherent::class;
    }

    protected function getViewPrefix(): string
    {
        return 'adherents';
    }

    protected function getShowRouteName(): string
    {
        return 'adherents.show';
    }

    protected function getListRouteName(): string
    {
        return 'adherents.list';
    }

    protected function getUpdateData(Request $request): array
    {
        return [
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
        ];
    }
}
