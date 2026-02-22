<?php

namespace App\Http\Controllers\Mrp;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HasCrudActions;
use App\Models\Mrp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowManufacturingOrder extends Controller
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
        return Mrp::class;
    }

    protected function getViewPrefix(): string
    {
        return 'mrp';
    }

    protected function getShowRouteName(): string
    {
        // Note: This uses old-style route, should be updated to named routes
        return 'mrp.show';
    }

    protected function getListRouteName(): string
    {
        // Note: This uses old-style route, should be updated to named routes
        return 'mrp.list';
    }

    protected function getUpdateData(Request $request): array
    {
        return [
            'ref' => $request->input('ref'),
        ];
    }
}
