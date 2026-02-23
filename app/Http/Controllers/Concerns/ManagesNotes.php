<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Trait for managing notes on models
 * 
 * Implements DRY principle by extracting common note management functionality
 */
trait ManagesNotes
{
    /**
     * Get the model instance
     */
    abstract protected function getModel(int $id): Model;
    
    /**
     * Get the view name
     */
    abstract protected function getViewName(): string;
    
    /**
     * Get the route name for redirect
     */
    abstract protected function getRouteName(): string;
    
    public function __invoke(Request $request, int $id): View|RedirectResponse
    {
        $action = $request->input('action', 'view');
        
        return match($action) {
            'edit' => $this->edit($request, $id),
            'update' => $this->update($request, $id),
            default => $this->show($request, $id),
        };
    }
    
    private function show(Request $request, int $id): View
    {
        $model = $this->getModel($id);
        
        return view($this->getViewName(), [
            $this->getModelKey() => $model,
            'action' => 'view',
        ]);
    }
    
    private function edit(Request $request, int $id): View
    {
        $model = $this->getModel($id);
        
        return view($this->getViewName(), [
            $this->getModelKey() => $model,
            'action' => 'edit',
        ]);
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        $model = $this->getModel($id);
        
        $notePublic = $request->input('note_public');
        $notePrivate = $request->input('note_private');
        
        if ($notePublic !== null) {
            $model->note_public = $notePublic;
        }
        
        if ($notePrivate !== null) {
            $model->note_private = $notePrivate;
        }
        
        $model->save();
        
        return redirect()
            ->route($this->getRouteName(), ['id' => $id])
            ->with('success', 'Notes updated successfully');
    }
    
    /**
     * Get the model key for view data
     */
    private function getModelKey(): string
    {
        return strtolower(class_basename($this->getModel(0)));
    }
}
