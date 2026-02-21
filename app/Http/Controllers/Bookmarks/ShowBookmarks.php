<?php

namespace App\Http\Controllers\Bookmarks;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowBookmarks extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $db, $langs, $user, $hookmanager;
        
        $action = GETPOST('action', 'alpha') ?: 'view';
        $id = GETPOSTINT('id');
        
        if (!$user->hasRight('bookmark', 'lire')) {
            accessforbidden();
        }
        
        require_once DOL_DOCUMENT_ROOT.'/bookmarks/class/bookmark.class.php';
        
        return match($action) {
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
        
        $title = (string) GETPOST("title", "alpha");
        $url = (string) GETPOST("url", "alpha");
        $target = GETPOST("target", "alpha");
        $userid = GETPOSTINT("userid");
        $position = GETPOSTINT("position");
        $backtopage = GETPOST('backtopage', 'alpha');
        
        $object = new \Bookmark($db);
        
        $permissiontoadd = $user->hasRight('bookmark', 'creer');
        
        $defaulttarget = 1;
        if ($url && !preg_match('/^http/i', $url)) {
            $defaulttarget = 0;
        }
        
        return view('bookmarks.create', [
            'object' => $object,
            'title' => $title,
            'url' => $url,
            'target' => GETPOSTISSET('target') ? GETPOSTINT('target') : $defaulttarget,
            'userid' => GETPOSTISSET('userid') ? GETPOSTINT('userid') : $user->id,
            'position' => GETPOSTISSET("position") ? GETPOSTINT("position") : $object->position,
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
        
        $title = (string) GETPOST("title", "alpha");
        $url = (string) GETPOST("url", "alpha");
        $urlsource = GETPOST("urlsource", "alpha");
        $target = GETPOST("target", "alpha");
        $userid = GETPOSTINT("userid");
        $position = GETPOSTINT("position");
        $backtopage = GETPOST('backtopage', 'alpha');
        
        $cancel = GETPOST('cancel', 'alpha');
        
        if ($cancel) {
            if (empty($backtopage)) {
                $backtopage = ($urlsource ? $urlsource : ((!empty($url) && !preg_match('/^http/i', $url)) ? $url : DOL_URL_ROOT.'/bookmarks/list.php'));
            }
            return redirect($backtopage);
        }
        
        $object = new \Bookmark($db);
        
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
        
        $object = new \Bookmark($db);
        $object->fetch($id);
        
        $permissiontoadd = $user->hasRight('bookmark', 'creer');
        $permissiontodelete = ($user->hasRight('bookmark', 'supprimer') || ($permissiontoadd && $object->fk_user == $user->id));
        
        $backtopage = GETPOST('backtopage', 'alpha');
        
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
        
        $title = (string) GETPOST("title", "alpha");
        $url = (string) GETPOST("url", "alpha");
        $urlsource = GETPOST("urlsource", "alpha");
        $target = GETPOST("target", "alpha");
        $userid = GETPOSTINT("userid");
        $position = GETPOSTINT("position");
        $backtopage = GETPOST('backtopage', 'alpha');
        
        $cancel = GETPOST('cancel', 'alpha');
        
        if ($cancel) {
            if (empty($backtopage)) {
                $backtopage = ($urlsource ? $urlsource : ((!empty($url) && !preg_match('/^http/i', $url)) ? $url : DOL_URL_ROOT.'/bookmarks/list.php'));
            }
            return redirect($backtopage);
        }
        
        $object = new \Bookmark($db);
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
        
        $object = new \Bookmark($db);
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
