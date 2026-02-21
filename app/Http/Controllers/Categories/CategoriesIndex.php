<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoriesIndex extends Controller
{
    public function __invoke(Request $request): View
    {
        global $db, $langs, $user;
        
        if (!$user->hasRight('categorie', 'read')) {
            accessforbidden();
        }
        
        require_once DOL_DOCUMENT_ROOT.'/categories/class/categorie.class.php';
        
        $categstatic = new \Categorie($db);
        
        // Get number of tags per category type
        $countobjects = [];
        $sql = "SELECT type as idtype, COUNT(rowid) as nb";
        $sql .= " FROM ".MAIN_DB_PREFIX."categorie";
        $sql .= " WHERE entity IN (".getEntity('category').")";
        $sql .= " GROUP BY type";
        $resql = $db->query($sql);
        
        if ($resql) {
            while ($obj = $db->fetch_object($resql)) {
                $countobjects[$obj->idtype] = $obj->nb;
            }
        } else {
            dol_print_error($db);
        }
        
        // Get list of category types
        $arrayofcateg = array();
        foreach ($categstatic->MAP_ID as $key => $idtype) {
            $arrayofcateg[$key] = array();
            $arrayofcateg[$key]['key'] = $key;
            $arrayofcateg[$key]['nb'] = $countobjects[$idtype] ?? 0;
            $arrayofcateg[$key]['label'] = $langs->transnoentitiesnoconv($categstatic::$MAP_TYPE_TITLE_AREA[$key]);
            $arrayofcateg[$key]['labelwithoutaccent'] = dol_string_unaccent($langs->transnoentitiesnoconv($categstatic::$MAP_TYPE_TITLE_AREA[$key]));
        }
        $arrayofcateg = dol_sort_array($arrayofcateg, 'labelwithoutaccent', 'asc', 1, 0, 1);
        
        // Calculate number of modules not auto-enabled
        $nbmodulesnotautoenabled = count($GLOBALS['conf']->modules);
        $listofmodulesautoenabled = array('user', 'agenda', 'fckeditor', 'export', 'import');
        foreach ($listofmodulesautoenabled as $moduleautoenable) {
            if (in_array($moduleautoenable, $GLOBALS['conf']->modules)) {
                $nbmodulesnotautoenabled--;
            }
        }
        
        return view('categories.index', [
            'arrayofcateg' => $arrayofcateg,
            'categstatic' => $categstatic,
            'nbmodulesnotautoenabled' => $nbmodulesnotautoenabled,
        ]);
    }
}
