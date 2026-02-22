<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HasCrudActions;
use App\Models\Contact;
use App\Models\Societe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowContact extends Controller
{
    use HasCrudActions;

    public function __invoke(Request $request): View|RedirectResponse
    {
        $action = $request->input('action', 'view');
        $id = $request->integer('id', 0);
        $socid = $request->integer('socid', 0);
        
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
        return Contact::class;
    }

    protected function getViewPrefix(): string
    {
        return 'contact';
    }

    protected function getShowRouteName(): string
    {
        return 'contact.show';
    }

    protected function getListRouteName(): string
    {
        return 'contact.list';
    }

    protected function loadModel(int $id): Model
    {
        return Contact::with('societe')->findOrFail($id);
    }

    protected function getAdditionalViewData(Request $request, ?Model $model = null): array
    {
        // Early return if we're just showing the contact
        if ($request->input('action') === 'view') {
            return [];
        }

        // For edit and create actions, load societes
        return [
            'societes' => Societe::orderBy('nom')->get(),
            'selectedSociete' => $this->getSelectedSociete($request),
        ];
    }

    protected function getUpdateData(Request $request): array
    {
        return [
            'lastname' => $request->input('lastname'),
            'firstname' => $request->input('firstname'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'phone_mobile' => $request->input('phone_mobile'),
            'phone_perso' => $request->input('phone_perso'),
            'fax' => $request->input('fax'),
            'poste' => $request->input('poste'),
            'address' => $request->input('address'),
            'zip' => $request->input('zip'),
            'town' => $request->input('town'),
            'fk_soc' => $request->integer('socid', 0),
            'fk_pays' => $request->integer('country_id', 0),
            'priv' => $request->integer('priv', 0),
            'note_public' => $request->input('note_public'),
            'note_private' => $request->input('note_private'),
        ];
    }

    private function getSelectedSociete(Request $request): ?Societe
    {
        $socid = $request->integer('socid', 0);
        
        if ($socid === 0) {
            return null;
        }
        
        return Societe::find($socid);
    }
}
