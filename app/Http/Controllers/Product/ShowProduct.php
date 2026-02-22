<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HasCrudActions;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowProduct extends Controller
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
        return Product::class;
    }

    protected function getViewPrefix(): string
    {
        return 'product';
    }

    protected function getShowRouteName(): string
    {
        return 'product.show';
    }

    protected function getListRouteName(): string
    {
        return 'product.list';
    }

    protected function getUpdateData(Request $request): array
    {
        return [
            'ref' => $request->input('ref'),
            'label' => $request->input('label'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'tva_tx' => $request->input('tva_tx'),
        ];
    }
}
