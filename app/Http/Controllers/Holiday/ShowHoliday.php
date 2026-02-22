<?php

namespace App\Http\Controllers\Holiday;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowHoliday extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $action = $request->input('action', 'view');
        $id = $request->integer('id', 0);
        
        return match($action) {
            'create', 'add' => view('holiday.create', ['action' => 'create']),
            'edit' => view('holiday.edit', ['holiday' => Holiday::findOrFail($id), 'action' => 'edit']),
            'update' => $this->update($request, $id),
            'delete' => $this->delete($request, $id),
            default => view('holiday.show', ['holiday' => Holiday::findOrFail($id), 'action' => 'view']),
        };
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        Holiday::findOrFail($id)->update(array_filter(['description' => $request->input('description')], fn($v) => $v));
        return redirect()->route('holiday.show', ['id' => $id])->with('success', 'Holiday updated');
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        Holiday::findOrFail($id)->delete();
        return redirect()->route('holiday.list')->with('success', 'Holiday deleted');
    }
}
