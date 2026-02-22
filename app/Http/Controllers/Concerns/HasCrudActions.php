<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Trait HasCrudActions
 * 
 * Provides reusable CRUD action methods following DRY and SOLID principles.
 * Eliminates duplicate code across 30+ Show controllers.
 */
trait HasCrudActions
{
    /**
     * The model class this controller manages
     */
    abstract protected function getModelClass(): string;

    /**
     * The view prefix for this controller's views
     */
    abstract protected function getViewPrefix(): string;

    /**
     * The route name for showing a single resource
     */
    abstract protected function getShowRouteName(): string;

    /**
     * The route name for listing resources
     */
    abstract protected function getListRouteName(): string;

    /**
     * Get additional view data (can be overridden in controllers)
     */
    protected function getAdditionalViewData(Request $request, ?Model $model = null): array
    {
        return [];
    }

    /**
     * Get data for update from request (can be overridden in controllers)
     */
    protected function getUpdateData(Request $request): array
    {
        return [];
    }

    /**
     * Load model or fail with 404
     */
    protected function loadModel(int $id): Model
    {
        $modelClass = $this->getModelClass();
        return $modelClass::findOrFail($id);
    }

    /**
     * Show a single resource
     */
    protected function show(Request $request, int $id): View
    {
        $model = $this->loadModel($id);
        $viewPrefix = $this->getViewPrefix();
        
        $data = array_merge([
            strtolower(class_basename($this->getModelClass())) => $model,
            'action' => 'view',
        ], $this->getAdditionalViewData($request, $model));

        return view("{$viewPrefix}.show", $data);
    }

    /**
     * Show edit form
     */
    protected function edit(Request $request, int $id): View
    {
        $model = $this->loadModel($id);
        $viewPrefix = $this->getViewPrefix();
        
        $data = array_merge([
            strtolower(class_basename($this->getModelClass())) => $model,
            'action' => 'edit',
        ], $this->getAdditionalViewData($request, $model));

        return view("{$viewPrefix}.edit", $data);
    }

    /**
     * Show create form
     */
    protected function create(Request $request): View
    {
        $viewPrefix = $this->getViewPrefix();
        
        $data = array_merge([
            'action' => 'create',
        ], $this->getAdditionalViewData($request));

        return view("{$viewPrefix}.create", $data);
    }

    /**
     * Update existing resource
     */
    protected function update(Request $request, int $id): RedirectResponse
    {
        $model = $this->loadModel($id);
        
        $data = $this->getUpdateData($request);
        $data = $this->filterEmptyValues($data);
        
        $model->update($data);
        
        $modelName = class_basename($this->getModelClass());
        
        return redirect()
            ->route($this->getShowRouteName(), ['id' => $id])
            ->with('success', "{$modelName} updated successfully");
    }

    /**
     * Delete resource
     */
    protected function delete(Request $request, int $id): RedirectResponse
    {
        $model = $this->loadModel($id);
        $model->delete();
        
        $modelName = class_basename($this->getModelClass());
        
        return redirect()
            ->route($this->getListRouteName())
            ->with('success', "{$modelName} deleted successfully");
    }

    /**
     * Filter empty values from array (extracted for DRY)
     */
    protected function filterEmptyValues(array $data): array
    {
        return array_filter($data, fn($value) => $value !== null && $value !== '');
    }
}
