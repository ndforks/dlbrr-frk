<?php

namespace App\Http\Controllers\ExpenseReport;

use App\Http\Controllers\Controller;
use App\Models\ExpenseReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowExpenseReport extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $action = $request->input('action', 'view');
        $id = $request->integer('id', 0);
        
        return match($action) {
            'create', 'add' => view('expensereport.create', ['action' => 'create']),
            'edit' => view('expensereport.edit', ['report' => ExpenseReport::findOrFail($id), 'action' => 'edit']),
            'update' => $this->update($request, $id),
            'delete' => $this->delete($request, $id),
            default => view('expensereport.show', ['report' => ExpenseReport::findOrFail($id), 'action' => 'view']),
        };
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        ExpenseReport::findOrFail($id)->update(array_filter(['ref' => $request->input('ref')], fn($v) => $v));
        return redirect("/expensereport/card.php?id={$id}")->with('success', 'Expense report updated');
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        ExpenseReport::findOrFail($id)->delete();
        return redirect('/expensereport/list.php')->with('success', 'Expense report deleted');
    }
}
