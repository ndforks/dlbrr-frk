<?php
/* Copyright (C) 2024 Laravel-Dolibarr Contributors <dev@laravel-dolibarr.org>
 * Copyright (C) 2024 GitHub Copilot AI Assistant
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

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
    
    /**
     * Get the model key name for view data
     */
    abstract protected function getModelKeyName(): string;
    
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
            $this->getModelKeyName() => $model,
            'action' => 'view',
        ]);
    }
    
    private function edit(Request $request, int $id): View
    {
        $model = $this->getModel($id);
        
        return view($this->getViewName(), [
            $this->getModelKeyName() => $model,
            'action' => 'edit',
        ]);
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        $model = $this->getModel($id);
        
        $notePublic = $request->input('note_public');
        $notePrivate = $request->input('note_private');
        
        if ($notePublic !== null) {
            // Sanitize HTML to prevent XSS attacks
            $model->note_public = strip_tags($notePublic, '<p><br><b><i><u><strong><em><ul><ol><li><a>');
        }
        
        if ($notePrivate !== null) {
            // Sanitize HTML to prevent XSS attacks
            $model->note_private = strip_tags($notePrivate, '<p><br><b><i><u><strong><em><ul><ol><li><a>');
        }
        
        $model->save();
        
        return redirect()
            ->route($this->getRouteName(), ['id' => $id])
            ->with('success', 'Notes updated successfully');
    }
}
