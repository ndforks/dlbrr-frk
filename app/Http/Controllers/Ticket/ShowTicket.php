<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowTicket extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $action = GETPOST('action', 'alpha') ?: 'view';
        $id = GETPOSTINT('id');
        
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
        $ticket = Ticket::with('societe')->findOrFail($id);
        return view('ticket.show', ['ticket' => $ticket, 'action' => 'view']);
    }
    
    private function edit(Request $request, int $id): View
    {
        $ticket = Ticket::with('societe')->findOrFail($id);
        return view('ticket.edit', ['ticket' => $ticket, 'action' => 'edit']);
    }
    
    private function create(Request $request): View
    {
        return view('ticket.create', ['action' => 'create']);
    }
    
    private function update(Request $request, int $id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);
        $data = ['subject' => GETPOST('subject', 'alpha'), 'fk_soc' => GETPOSTINT('socid')];
        $ticket->update(array_filter($data, fn($v) => $v !== null && $v !== ''));
        return redirect("/ticket/card.php?id={$id}")->with('success', 'Ticket updated');
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        Ticket::findOrFail($id)->delete();
        return redirect('/ticket/list.php')->with('success', 'Ticket deleted');
    }
}
