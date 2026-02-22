<?php

namespace App\Http\Controllers\ExpenseReport;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HasCrudActions;
use App\Models\ExpenseReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowExpenseReport extends Controller
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
        return ExpenseReport::class;
    }

    protected function getViewPrefix(): string
    {
        return 'expensereport';
    }

    protected function getShowRouteName(): string
    {
        return 'expensereport.show';
    }

    protected function getListRouteName(): string
    {
        return 'expensereport.list';
    }

    protected function getUpdateData(Request $request): array
    {
        return [
            'ref' => $request->input('ref'),
        ];
    }
}
