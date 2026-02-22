<?php

namespace App\Http\Controllers\Don;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HasCrudActions;
use App\Models\Don;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowDon extends Controller
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
        return Don::class;
    }

    protected function getViewPrefix(): string
    {
        return 'don';
    }

    protected function getShowRouteName(): string
    {
        return 'don.show';
    }

    protected function getListRouteName(): string
    {
        return 'don.list';
    }

    protected function loadModel(int $id): Model
    {
        return Don::with('societe')->findOrFail($id);
    }

    protected function getUpdateData(Request $request): array
    {
        return [
            'fk_soc' => $request->integer('socid', 0),
        ];
    }
}
