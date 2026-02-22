<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowLoan extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $action = $request->input('action', 'view');
        $id = $request->integer('id', 0);
        
        return match($action) {
            'create', 'add' => view('loan.create', ['action' => 'create']),
            'edit' => view('loan.edit', ['loan' => Loan::findOrFail($id), 'action' => 'edit']),
            'update' => $this->update($request, $id),
            'delete' => $this->delete($request, $id),
            default => view('loan.show', ['loan' => Loan::findOrFail($id), 'action' => 'view']),
        };
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        Loan::findOrFail($id)->update(array_filter(['label' => $request->input('label')], fn($v) => $v));
        return redirect("/loan/card.php?id={$id}")->with('success', 'Loan updated');
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        Loan::findOrFail($id)->delete();
        return redirect('/loan/list.php')->with('success', 'Loan deleted');
    }
}
