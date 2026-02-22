<?php

namespace App\Http\Controllers\Bookmarks;
use App\Modules\Core\Classes\ExtraFields;
use App\Modules\Bookmarks\Classes\Bookmark;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookmarksIndex extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $db, $langs, $user, $conf, $hookmanager;
        
        $action = $request->input('action');
        $id = $request->integer('id', 0);
        
        if (!$user->hasRight('bookmark', 'lire')) {
            accessforbidden();
        }        return match($action) {
            'delete' => $this->delete($request, $id),
            default => $this->index($request),
        };
    }
    
    private function index(Request $request): View
    {
        global $db, $langs, $user, $conf, $hookmanager;
        
        $massaction = $request->input('massaction', []);
        $show_files = $request->integer('show_files', 0);
        $confirm = $request->input('confirm');
        $cancel = $request->input('cancel');
        $toselect = $request->input('toselect', []);
        $contextpage = $request->input('contextpage') ? $request->input('contextpage') : 'bookmarklist';
        $backtopage = $request->input('backtopage');
        $optioncss = $request->input('optioncss');
        $mode = $request->input('mode');
        
        $search_title = $request->input('search_title');
        
        $limit = $request->integer('limit', 0) ? $request->integer('limit', 0) : $conf->liste_limit;
        $sortfield = $request->input('sortfield');
        $sortorder = $request->input('sortorder');
        $page = $request->has('pageplusone') ? ($request->integer('pageplusone', 0) - 1) : $request->integer('page', 0);
        
        if (empty($page) || $page < 0 || $request->input('button_search') || $request->input('button_removefilter')) {
            $page = 0;
        }
        
        $offset = $limit * $page;
        
        if (!$sortfield) {
            $sortfield = 'b.position';
        }
        if (!$sortorder) {
            $sortorder = 'ASC';
        }
        
        $object = new Bookmark($db);
        $extrafields = new ExtraFields($db);
        $arrayfields = array();
        $hookmanager->initHooks(array('bookmarklist'));
        
        $object->fields = dol_sort_array($object->fields, 'position');
        $arrayfields = dol_sort_array($arrayfields, 'position');
        
        $permissiontoread = $user->hasRight('bookmark', 'lire');
        $permissiontoadd = $user->hasRight('bookmark', 'creer');
        $permissiontodelete = ($user->hasRight('bookmark', 'supprimer') || ($permissiontoadd && $object->fk_user == $user->id));
        
        // Build SQL query
        $sql = "SELECT b.rowid, b.dateb, b.fk_user, b.url, b.target, b.title, b.favicon, b.position,";
        $sql .= " u.login, u.lastname, u.firstname";
        
        if (!empty($extrafields->attributes[$object->table_element]['label'])) {
            foreach ($extrafields->attributes[$object->table_element]['label'] as $key => $val) {
                $sql .= ($extrafields->attributes[$object->table_element]['type'][$key] != 'separate' ? ", ef.".$key." as options_".$key : '');
            }
        }
        
        $parameters = array();
        $reshook = $hookmanager->executeHooks('printFieldListSelect', $parameters, $object);
        $sql .= $hookmanager->resPrint;
        $sql = preg_replace('/,\s*$/', '', $sql);
        
        $sqlfields = $sql;
        
        $sql .= " FROM ".MAIN_DB_PREFIX.$object->table_element." as b LEFT JOIN ".MAIN_DB_PREFIX."user as u ON b.fk_user=u.rowid";
        $sql .= " WHERE 1=1";
        
        if ($search_title) {
            $sql .= natural_search('title', $search_title);
        }
        
        $sql .= " AND b.entity IN (".getEntity('bookmark').")";
        
        if (!$user->admin) {
            $sql .= " AND (b.fk_user = ".((int) $user->id)." OR b.fk_user is NULL OR b.fk_user = 0)";
        }
        
        // Count total records
        $nbtotalofrecords = '';
        if (!getDolGlobalInt('MAIN_DISABLE_FULL_SCANLIST')) {
            $sqlforcount = preg_replace('/^'.preg_quote($sqlfields, '/').'/', 'SELECT COUNT(*) as nbtotalofrecords', $sql);
            $sqlforcount = preg_replace('/GROUP BY .*$/', '', $sqlforcount);
            $resql = $db->query($sqlforcount);
            
            if ($resql) {
                $objforcount = $db->fetch_object($resql);
                $nbtotalofrecords = $objforcount->nbtotalofrecords;
            }
            
            if (($page * $limit) > (int) $nbtotalofrecords) {
                $page = 0;
                $offset = 0;
            }
            
            $db->free($resql);
        }
        
        $sql .= $db->order($sortfield.", position", $sortorder);
        
        if ($limit) {
            $sql .= $db->plimit($limit + 1, $offset);
        }
        
        $resql = $db->query($sql);
        
        if (!$resql) {
            dol_print_error($db);
            exit;
        }
        
        $num = $db->num_rows($resql);
        
        $bookmarks = array();
        $i = 0;
        $imaxinloop = ($limit ? min($num, $limit) : $num);
        
        while ($i < $imaxinloop) {
            $obj = $db->fetch_object($resql);
            if (empty($obj)) {
                break;
            }
            $bookmarks[] = $obj;
            $i++;
        }
        
        $db->free($resql);
        
        return view('bookmarks.index', [
            'bookmarks' => $bookmarks,
            'object' => $object,
            'permissiontoread' => $permissiontoread,
            'permissiontoadd' => $permissiontoadd,
            'permissiontodelete' => $permissiontodelete,
            'num' => $num,
            'nbtotalofrecords' => $nbtotalofrecords,
            'page' => $page,
            'limit' => $limit,
            'sortfield' => $sortfield,
            'sortorder' => $sortorder,
            'search_title' => $search_title,
            'contextpage' => $contextpage,
            'optioncss' => $optioncss,
            'mode' => $mode,
            'massaction' => $massaction,
            'toselect' => $toselect,
        ]);
    }
    
    private function delete(Request $request, int $id): RedirectResponse
    {
        global $db, $user;
        
        if (!$user->hasRight('bookmark', 'supprimer')) {
            accessforbidden();
        }
        
        $object = new Bookmark($db);
        $object->fetch($id);
        
        $res = $object->delete($user);
        
        if ($res > 0) {
            return redirect()->route('bookmarks.index');
        } else {
            setEventMessages($object->error, $object->errors, 'errors');
            return redirect()->route('bookmarks.index');
        }
    }
}
