<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HasCrudActions;
use App\Models\Loan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowLoan extends Controller
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
        return Loan::class;
    }

    protected function getViewPrefix(): string
    {
        return 'loan';
    }

    protected function getShowRouteName(): string
    {
        return 'loan.show';
    }

    protected function getListRouteName(): string
    {
        return 'loan.list';
    }

    protected function getUpdateData(Request $request): array
    {
        return [
            'label' => $request->input('label'),
        ];
    }
}
