<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowProduct extends Controller
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
        $product = Product::findOrFail($id);
        return view('product.show', ['product' => $product, 'action' => 'view']);
    }
    
    private function edit(Request $request, int $id): View
    {
        $product = Product::findOrFail($id);
        return view('product.edit', ['product' => $product, 'action' => 'edit']);
    }
    
    private function create(Request $request): View
    {
        return view('product.create', ['action' => 'create']);
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        $product = Product::findOrFail($id);
        
        $data = [
            'ref' => $request->input('ref'),
            'label' => $request->input('label'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'tva_tx' => $request->input('tva_tx'),
        ];
        
        $data = array_filter($data, fn($value) => $value !== null && $value !== '');
        $product->update($data);
        
        return redirect()->route('product.show', ['id' => $id])->with('success', 'Product updated');
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        Product::findOrFail($id)->delete();
        return redirect()->route('product.list')->with('success', 'Product deleted');
    }
}
