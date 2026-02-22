<?php

namespace App\Http\Controllers\Variants;
use App\Modules\Core\Classes\ExtraFields;
use App\Modules\Variants\Classes\ProductAttribute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListVariants extends Controller
{
    /**
     * Handle the incoming request.
     * Displays list of product attributes with search/filter capabilities.
     */
    public function __invoke(Request $request): View
    {
        global $conf, $db, $langs, $user, $hookmanager;
        
        require_once DOL_DOCUMENT_ROOT.'/variants/class/ProductAttribute.class.php';        $langs->loadLangs(['products', 'other']);
        
        if (!isModEnabled('variants')) {
            accessforbidden('Module not enabled');
        }
        if ($user->socid > 0) {
            accessforbidden();
        }
        
        $hookmanager->initHooks(['productattributelist']);
        restrictedArea($user, 'variants');
        
        $object = new ProductAttribute($db);
        $extrafields = new ExtraFields($db);
        $extrafields->fetch_name_optionals_label($object->table_element);
        
        $limit = GETPOSTINT('limit') ?: $conf->liste_limit;
        $sortfield = GETPOST('sortfield', 'aZ09comma') ?: 't.position';
        $sortorder = GETPOST('sortorder', 'aZ09comma') ?: 'ASC';
        $page = GETPOSTINT('page') ?: 0;
        $offset = $limit * $page;
        
        $search = [];
        foreach ($object->fields as $key => $val) {
            if (GETPOST('search_'.$key, 'alpha') !== '') {
                $search[$key] = GETPOST('search_'.$key, 'alpha');
            }
        }
        $search['nb_of_values'] = GETPOST('search_nb_of_values', 'alpha');
        $search['nb_products'] = GETPOST('search_nb_products', 'alpha');
        
        $sql = 'SELECT t.rowid, t.ref, t.label, t.position';
        $sql .= ', COUNT(DISTINCT pav.rowid) as nb_of_values';
        $sql .= ', COUNT(DISTINCT pc.fk_product_child) as nb_products';
        $sql .= ' FROM '.MAIN_DB_PREFIX.'product_attribute as t';
        $sql .= ' LEFT JOIN '.MAIN_DB_PREFIX.'product_attribute_value as pav ON pav.fk_product_attribute = t.rowid';
        $sql .= ' LEFT JOIN '.MAIN_DB_PREFIX.'product_attribute_combination2val as pc2v ON pc2v.fk_prod_attr_val = pav.rowid';
        $sql .= ' LEFT JOIN '.MAIN_DB_PREFIX.'product_attribute_combination as pc ON pc.rowid = pc2v.fk_prod_combination';
        $sql .= ' WHERE t.entity IN ('.getEntity('product').')';
        
        if (!empty($search['ref'])) {
            $sql .= natural_search('t.ref', $search['ref']);
        }
        if (!empty($search['label'])) {
            $sql .= natural_search('t.label', $search['label']);
        }
        
        $sql .= ' GROUP BY t.rowid, t.ref, t.label, t.position';
        $sql .= $db->order($sortfield, $sortorder);
        $sql .= $db->plimit($limit + 1, $offset);
        
        $resql = $db->query($sql);
        $num = 0;
        $attributes = [];
        if ($resql) {
            $num = $db->num_rows($resql);
            $i = 0;
            while ($i < min($num, $limit)) {
                $obj = $db->fetch_object($resql);
                if ($obj) {
                    $attributes[] = $obj;
                }
                $i++;
            }
            $db->free($resql);
        }
        
        return view('variants.list', [
            'attributes' => $attributes,
            'total' => $num,
            'limit' => $limit,
            'page' => $page,
            'sortfield' => $sortfield,
            'sortorder' => $sortorder,
            'search' => $search,
        ]);
    }
}
