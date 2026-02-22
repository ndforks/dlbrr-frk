<?php

namespace App\Http\Controllers\Bookmarks;
use App\Modules\Bookmarks\Classes\Bookmark;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowBookmarks extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $db, $langs, $user, $hookmanager;
        
        $action = $request->input('action', 'view');
        $id = $request->integer('id', 0);
        
        if (!$user->hasRight('bookmark', 'lire')) {
            accessforbidden();
        }        return match($action) {
            'create' => $this->create($request),
            'add', 'addproduct' => $this->add($request),
            'edit' => $this->edit($request, $id),
            'update' => $this->update($request, $id),
            default => $this->show($request, $id),
        };
    }
    
    private function create(Request $request): View
    {
        global $db, $langs, $user;
        
        $title = (string) $request->input('title');
        $url = (string) $request->input('url');
        $target = $request->input('target');
        $userid = $request->integer('userid', 0);
        $position = $request->integer('position', 0);
        $backtopage = $request->input('backtopage');
        
        $object = new Bookmark($db);
        
        $permissiontoadd = $user->hasRight('bookmark', 'creer');
        
        $defaulttarget = 1;
        if ($url && !preg_match('/^http/i', $url)) {
            $defaulttarget = 0;
        }
        
        return view('bookmarks.create', [
            'object' => $object,
            'title' => $title,
            'url' => $url,
            'target' => $request->has('target') ? $request->integer('target', 0) : $defaulttarget,
            'userid' => $request->has('userid') ? $request->integer('userid', 0) : $user->id,
            'position' => $request->has('position') ? $request->integer('position', 0) : $object->position,
            'backtopage' => $backtopage,
            'permissiontoadd' => $permissiontoadd,
        ]);
    }
    
    private function add(Request $request): RedirectResponse|View
    {
        global $db, $langs, $user;
        
        if (!$user->hasRight('bookmark', 'creer')) {
            accessforbidden();
        }
        
        $title = (string) $request->input('title');
        $url = (string) $request->input('url');
        $urlsource = $request->input('urlsource');
        $target = $request->input('target');
        $userid = $request->integer('userid', 0);
        $position = $request->integer('position', 0);
        $backtopage = $request->input('backtopage');
        
        $cancel = $request->input('cancel');
        
        if ($cancel) {
            if (empty($backtopage)) {
                $backtopage = ($urlsource ? $urlsource : ((!empty($url) && !preg_match('/^http/i', $url)) ? $url : DOL_URL_ROOT.'/bookmarks/list.php'));
            }
            return redirect($backtopage);
        }
        
        $object = new Bookmark($db);
        
        if (!empty($userid)) {
            $object->fk_user = $userid;
        }
        $object->title = $title;
        $object->url = $url;
        $object->target = $target;
        $object->position = $position;
        
        $error = 0;
        
        if (!$title) {
            $error++;
            setEventMessages($langs->transnoentities("ErrorFieldRequired", $langs->trans("BookmarkTitle")), null, 'errors');
        }
        
        if (!$url) {
            $error++;
            setEventMessages($langs->transnoentities("ErrorFieldRequired", $langs->trans("UrlOrLink")), null, 'errors');
        }
        
        if (!$error) {
            $object->favicon = 'none';
            $res = $object->create();
            
            if ($res > 0) {
                if (empty($backtopage)) {
                    $backtopage = ($urlsource ? $urlsource : ((!empty($url) && !preg_match('/^http/i', $url)) ? $url : DOL_URL_ROOT.'/bookmarks/list.php'));
                }
                return redirect($backtopage);
            } else {
                if ($object->errno == 'DB_ERROR_RECORD_ALREADY_EXISTS') {
                    $langs->load("errors");
                    setEventMessages($langs->transnoentities("WarningBookmarkAlreadyExists"), null, 'warnings');
                } else {
                    setEventMessages($object->error, $object->errors, 'errors');
                }
            }
        }
        
        return $this->create($request);
    }
    
    private function edit(Request $request, int $id): View
    {
        global $db, $langs, $user;
        
        $object = new Bookmark($db);
        $object->fetch($id);
        
        $permissiontoadd = $user->hasRight('bookmark', 'creer');
        $permissiontodelete = ($user->hasRight('bookmark', 'supprimer') || ($permissiontoadd && $object->fk_user == $user->id));
        
        $backtopage = $request->input('backtopage');
        
        return view('bookmarks.edit', [
            'object' => $object,
            'permissiontoadd' => $permissiontoadd,
            'permissiontodelete' => $permissiontodelete,
            'backtopage' => $backtopage,
        ]);
    }
    
    private function update(Request $request, int $id): RedirectResponse|View
    {
        global $db, $langs, $user;
        
        if (!$user->hasRight('bookmark', 'creer')) {
            accessforbidden();
        }
        
        $title = (string) $request->input('title');
        $url = (string) $request->input('url');
        $urlsource = $request->input('urlsource');
        $target = $request->input('target');
        $userid = $request->integer('userid', 0);
        $position = $request->integer('position', 0);
        $backtopage = $request->input('backtopage');
        
        $cancel = $request->input('cancel');
        
        if ($cancel) {
            if (empty($backtopage)) {
                $backtopage = ($urlsource ? $urlsource : ((!empty($url) && !preg_match('/^http/i', $url)) ? $url : DOL_URL_ROOT.'/bookmarks/list.php'));
            }
            return redirect($backtopage);
        }
        
        $object = new Bookmark($db);
        $object->fetch($id);
        
        if (!empty($userid)) {
            $object->fk_user = $userid;
        }
        $object->title = $title;
        $object->url = $url;
        $object->target = $target;
        $object->position = $position;
        
        $error = 0;
        
        if (!$title) {
            $error++;
            setEventMessages($langs->transnoentities("ErrorFieldRequired", $langs->trans("BookmarkTitle")), null, 'errors');
        }
        
        if (!$url) {
            $error++;
            setEventMessages($langs->transnoentities("ErrorFieldRequired", $langs->trans("UrlOrLink")), null, 'errors');
        }
        
        if (!$error) {
            $object->favicon = 'none';
            $res = $object->update();
            
            if ($res > 0) {
                if (empty($backtopage)) {
                    $backtopage = ($urlsource ? $urlsource : ((!empty($url) && !preg_match('/^http/i', $url)) ? $url : DOL_URL_ROOT.'/bookmarks/list.php'));
                }
                return redirect($backtopage);
            } else {
                if ($object->errno == 'DB_ERROR_RECORD_ALREADY_EXISTS') {
                    $langs->load("errors");
                    setEventMessages($langs->transnoentities("WarningBookmarkAlreadyExists"), null, 'warnings');
                } else {
                    setEventMessages($object->error, $object->errors, 'errors');
                }
            }
        }
        
        return $this->edit($request, $id);
    }
    
    private function show(Request $request, int $id): View
    {
        global $db, $langs, $user;
        
        $object = new Bookmark($db);
        $object->fetch($id);
        
        $permissiontoread = $user->hasRight('bookmark', 'lire');
        $permissiontoadd = $user->hasRight('bookmark', 'creer');
        $permissiontodelete = ($user->hasRight('bookmark', 'supprimer') || ($permissiontoadd && $object->fk_user == $user->id));
        
        return view('bookmarks.show', [
            'object' => $object,
            'permissiontoread' => $permissiontoread,
            'permissiontoadd' => $permissiontoadd,
            'permissiontodelete' => $permissiontodelete,
        ]);
    }
}
