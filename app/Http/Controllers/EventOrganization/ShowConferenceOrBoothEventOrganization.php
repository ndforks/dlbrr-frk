<?php

namespace App\Http\Controllers\EventOrganization;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowConferenceOrBoothEventOrganization extends Controller
{
    /**
     * Handle the incoming request.
     * Displays, edits, creates, or updates a conference or booth.
     */
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $db, $langs, $user, $conf;
        
        // Load translation files
        $langs->loadLangs(['eventorganization', 'projects']);
        
        $action = GETPOST('action', 'aZ09') ?: 'view';
        $id = GETPOSTINT('id');
        $ref = GETPOST('ref', 'alpha');
        $withproject = GETPOSTINT('withproject');
        
        // Security check
        if ($user->socid > 0) {
            accessforbidden();
        }
        
        // Load object
        require_once DOL_DOCUMENT_ROOT.'/eventorganization/class/conferenceorbooth.class.php';
        require_once DOL_DOCUMENT_ROOT.'/projet/class/project.class.php';
        
        $object = new \ConferenceOrBooth($db);
        $projectstatic = new \Project($db);
        
        if ($id > 0 || !empty($ref)) {
            $result = $object->fetch($id, $ref);
            if ($result > 0) {
                restrictedArea($user, 'projet', $object->fk_project, 'projet&project');
            }
        }
        
        // Handle different actions
        return match($action) {
            'create', 'add' => $this->create($request, $object, $projectstatic),
            'edit' => $this->edit($request, $object, $projectstatic),
            'update' => $this->update($request, $object),
            'delete' => $this->delete($request, $object),
            default => $this->show($request, $object, $projectstatic),
        };
    }
    
    /**
     * Show conference or booth details
     */
    private function show(Request $request, \ConferenceOrBooth $object, \Project $projectstatic): View
    {
        global $db, $langs;
        
        if ($object->id > 0) {
            $object->fetch_optionals();
            $projectstatic->fetch($object->fk_project);
            
            if (!empty($projectstatic->socid)) {
                $projectstatic->fetch_thirdparty();
            }
            
            $object->project = clone $projectstatic;
        }
        
        $withproject = GETPOSTINT('withproject');
        
        return view('eventorganization.conferenceorbooth.show', [
            'object' => $object,
            'project' => $projectstatic,
            'withproject' => $withproject,
            'action' => 'view',
        ]);
    }
    
    /**
     * Show edit form
     */
    private function edit(Request $request, \ConferenceOrBooth $object, \Project $projectstatic): View
    {
        global $db, $langs;
        
        $object->fetch_optionals();
        $projectstatic->fetch($object->fk_project);
        
        if (!empty($projectstatic->socid)) {
            $projectstatic->fetch_thirdparty();
        }
        
        $withproject = GETPOSTINT('withproject');
        
        return view('eventorganization.conferenceorbooth.edit', [
            'object' => $object,
            'project' => $projectstatic,
            'withproject' => $withproject,
            'action' => 'edit',
        ]);
    }
    
    /**
     * Show create form
     */
    private function create(Request $request, \ConferenceOrBooth $object, \Project $projectstatic): View
    {
        global $db, $langs;
        
        $fk_project = GETPOSTINT('fk_project');
        $projectstatic->fetch($fk_project);
        
        if (!empty($projectstatic->socid)) {
            $projectstatic->fetch_thirdparty();
        }
        
        $withproject = GETPOSTINT('withproject');
        
        return view('eventorganization.conferenceorbooth.create', [
            'object' => $object,
            'project' => $projectstatic,
            'withproject' => $withproject,
            'action' => 'create',
        ]);
    }
    
    /**
     * Update existing conference or booth
     */
    private function update(Request $request, \ConferenceOrBooth $object): RedirectResponse
    {
        global $db, $user, $langs;
        
        // Get form data and update object fields
        foreach ($object->fields as $key => $val) {
            if (array_key_exists($key, $_POST)) {
                $object->$key = GETPOST($key, $val['type']);
            }
        }
        
        // Set extrafields
        $extrafields = new \ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        $ret = $extrafields->setOptionalsFromPost(null, $object, '@GETPOSTISSET');
        
        if ($ret < 0) {
            setEventMessages($object->error, $object->errors, 'errors');
            return redirect('/eventorganization/conferenceorbooth_card.php?id='.$object->id.'&action=edit');
        }
        
        // Update the object
        $result = $object->update($user);
        
        if ($result > 0) {
            setEventMessages($langs->trans('RecordSaved'), null, 'mesgs');
        } else {
            setEventMessages($object->error, $object->errors, 'errors');
        }
        
        $withproject = GETPOSTINT('withproject');
        $withProjectUrl = $withproject ? '&withproject=1' : '';
        
        return redirect('/eventorganization/conferenceorbooth_card.php?id='.$object->id.$withProjectUrl);
    }
    
    /**
     * Delete conference or booth
     */
    private function delete(Request $request, \ConferenceOrBooth $object): RedirectResponse
    {
        global $db, $user;
        
        $confirm = GETPOST('confirm', 'alpha');
        
        if ($confirm === 'yes') {
            $object->delete($user);
            $withproject = GETPOSTINT('withproject');
            
            if ($withproject) {
                return redirect('/eventorganization/conferenceorbooth_list.php?withproject=1&fk_project='.$object->fk_project);
            }
            return redirect('/eventorganization/conferenceorbooth_list.php');
        }
        
        return redirect()->back();
    }
}
