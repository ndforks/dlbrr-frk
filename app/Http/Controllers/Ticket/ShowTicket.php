<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HasCrudActions;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowTicket extends Controller
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
        return Ticket::class;
    }

    protected function getViewPrefix(): string
    {
        return 'ticket';
    }

    protected function getShowRouteName(): string
    {
        return 'ticket.show';
    }

    protected function getListRouteName(): string
    {
        return 'ticket.list';
    }

    protected function loadModel(int $id): Model
    {
        return Ticket::with('societe')->findOrFail($id);
    }

    protected function getUpdateData(Request $request): array
    {
        return [
            'subject' => $request->input('subject'),
            'fk_soc' => $request->integer('socid', 0),
        ];
    }
}
