@php
/* Position Card View */
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formcompany.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formprojet.class.php';

$title = $langs->trans("Position");
llxHeader('', $title, '');

if ($job->id > 0) {
    $res = $job->fetch_optionals();
    $head = jobPrepareHead($job);
    print dol_get_fiche_head($head, 'position', $langs->trans("Workstation"), -1, $job->picto);

    $linkback = '<a href="'.dol_buildpath('/hrm/position_list.php', 1).'?restore_lastsearch_values=1'.(!empty($fk_job) ? '&fk_job='.$fk_job : '').'">'.$langs->trans("BackToList").'</a>';

    $morehtmlref = '<div class="refid">';
    $morehtmlref .= $job->label;
    $morehtmlref .= '</div>';

    dol_banner_tab($job, 'fk_job', $linkback, 1, 'rowid', 'rowid', $morehtmlref);

    print '<div class="fichecenter">';
    print '<div class="fichehalfleft">';
    print '<div class="underbanner clearboth"></div>';
    print '<table class="border centpercent tableforfield">'."\n";

    $job->fields['label']['visible'] = 0;
    include DOL_DOCUMENT_ROOT.'/core/tpl/commonfields_view.tpl.php';
    include DOL_DOCUMENT_ROOT.'/core/tpl/extrafields_view.tpl.php';

    print '</table>';
    print '</div>';
    print '</div>';
    print '<div class="clearboth"></div>';
    print dol_get_fiche_end();

    $object = $objectposition;
    $contextpage = GETPOST('contextpage', 'aZ') ? GETPOST('contextpage', 'aZ') : 'jobpositionlist';
    $optioncss = GETPOST('optioncss', 'aZ');
    $massaction = GETPOST('massaction', 'alpha');
    $toselect = GETPOST('toselect', 'array:int');
    $search_all = GETPOST('search_all', 'alphanohtml');
    $search = array();
    foreach ($objectposition->fields as $key => $val) {
        if (GETPOST('search_'.$key, 'alpha') !== '') {
            $search[$key] = GETPOST('search_'.$key, 'alpha');
        }
        if (preg_match('/^(date|timestamp|datetime)/', $val['type'])) {
            $search[$key.'_dtstart'] = dol_mktime(0, 0, 0, GETPOSTINT('search_'.$key.'_dtstartmonth'), GETPOSTINT('search_'.$key.'_dtstartday'), GETPOSTINT('search_'.$key.'_dtstartyear'));
            $search[$key.'_dtend'] = dol_mktime(23, 59, 59, GETPOSTINT('search_'.$key.'_dtendmonth'), GETPOSTINT('search_'.$key.'_dtendday'), GETPOSTINT('search_'.$key.'_dtendyear'));
        }
    }

    $sql = 'SELECT ';
    $sql .= $object->getFieldList('t');
    if (!empty($extrafields->attributes[$object->table_element]['label'])) {
        foreach ($extrafields->attributes[$object->table_element]['label'] as $key => $val) {
            $sql .= ($extrafields->attributes[$object->table_element]['type'][$key] != 'separate' ? ", ef.".$key." as options_".$key.', ' : '');
        }
    }
    $sql = preg_replace('/,\s*$/', '', $sql);
    $sql .= " FROM ".MAIN_DB_PREFIX.$object->table_element." as t";
    if (isset($extrafields->attributes[$object->table_element]['label']) && is_array($extrafields->attributes[$object->table_element]['label']) && count($extrafields->attributes[$object->table_element]['label'])) {
        $sql .= " LEFT JOIN ".MAIN_DB_PREFIX.$object->table_element."_extrafields as ef on (t.rowid = ef.fk_object)";
    }
    if ($object->ismultientitymanaged == 1) {
        $sql .= " WHERE t.entity IN (".getEntity($object->element).")";
    } else {
        $sql .= " WHERE 1 = 1";
    }
    $sql .= " AND t.fk_job = ".((int) $fk_job)." ";

    foreach ($search as $key => $val) {
        if (array_key_exists($key, $object->fields)) {
            if ($key == 'status' && $search[$key] == -1) {
                continue;
            }
            $mode_search = (($object->isInt($object->fields[$key]) || $object->isFloat($object->fields[$key])) ? 1 : 0);
            if ((strpos($object->fields[$key]['type'], 'integer:') === 0) || (strpos($object->fields[$key]['type'], 'sellist:') === 0) || !empty($object->fields[$key]['arrayofkeyval'])) {
                if ($search[$key] == '-1' || $search[$key] === '0') {
                    $search[$key] = '';
                }
                $mode_search = 2;
            }
            if ($search[$key] != '') {
                $sql .= natural_search($key, $search[$key], (($key == 'status') ? 2 : $mode_search));
            }
        }
    }

    $sql .= $db->order($sortfield, $sortorder);

    $nbtotalofrecords = '';
    if (!getDolGlobalInt('MAIN_DISABLE_FULL_SCANLIST')) {
        $resql = $db->query($sql);
        $nbtotalofrecords = $db->num_rows($resql);
        if (($page * $limit) > (int) $nbtotalofrecords) {
            $page = 0;
            $offset = 0;
        }
    }
    if (is_numeric($nbtotalofrecords) && ($limit > $nbtotalofrecords || empty($limit))) {
        $num = $nbtotalofrecords;
    } else {
        if ($limit) {
            $sql .= $db->plimit($limit + 1, $offset);
        }
        $resql = $db->query($sql);
        if (!$resql) {
            dol_print_error($db);
            exit;
        }
        $num = $db->num_rows($resql);
    }

    if ($num == 1 && getDolGlobalString('MAIN_SEARCH_DIRECT_OPEN_IF_ONLY_ONE') && $search_all && !$page) {
        $obj = $db->fetch_object($resql);
        $id = $obj->rowid;
        header("Location: ".dol_buildpath('/hrm/position.php', 1).'?id='.$id);
        exit;
    }

    $arrayofselected = is_array($toselect) ? $toselect : array();

    $param = 'fk_job='.$fk_job;
    if (!empty($contextpage) && $contextpage != $_SERVER["PHP_SELF"]) {
        $param .= '&contextpage='.urlencode($contextpage);
    }
    if ($limit > 0 && $limit != $conf->liste_limit) {
        $param .= '&limit='.((int) $limit);
    }
    foreach ($search as $key => $val) {
        if (is_array($search[$key]) && count($search[$key])) {
            foreach ($search[$key] as $skey) {
                $param .= '&search_'.$key.'[]='.urlencode($skey);
            }
        } else {
            $param .= '&search_'.$key.'='.urlencode($search[$key]);
        }
    }
    if ($optioncss != '') {
        $param .= '&optioncss='.urlencode($optioncss);
    }

    $arrayofmassactions = array();
    if ($permissiontodelete) {
        $arrayofmassactions['predelete'] = img_picto('', 'delete', 'class="pictofixedwidth"').$langs->trans("Delete");
    }
    if (GETPOSTINT('nomassaction') || in_array($massaction, array('presend', 'predelete'))) {
        $arrayofmassactions = array();
    }
    $massactionbutton = $form->selectMassAction('', $arrayofmassactions);

    print '<form method="POST" id="searchFormList" action="'.$_SERVER["PHP_SELF"].'?fk_job='.$fk_job.'">'."\n";
    if ($optioncss != '') {
        print '<input type="hidden" name="optioncss" value="'.$optioncss.'">';
    }
    print '<input type="hidden" name="token" value="'.newToken().'">';
    print '<input type="hidden" name="formfilteraction" id="formfilteraction" value="list">';
    print '<input type="hidden" name="action" value="list">';
    print '<input type="hidden" name="massaction" value="'.$massaction.'">';
    print '<input type="hidden" name="sortfield" value="'.$sortfield.'">';
    print '<input type="hidden" name="sortorder" value="'.$sortorder.'">';
    print '<input type="hidden" name="page" value="'.$page.'">';
    print '<input type="hidden" name="contextpage" value="'.$contextpage.'">';

    $newcardbutton = '';
    $newcardbutton .= dolGetButtonTitle($langs->trans('New'), '', 'fa fa-plus-circle', dol_buildpath('/hrm/position.php', 1).'?action=create&backtopage='.urlencode($_SERVER['PHP_SELF']).'&fk_job='.((int) $fk_job), '', $permissiontoadd);

    print_barre_liste($title, $page, $_SERVER["PHP_SELF"], $param, $sortfield, $sortorder, $massactionbutton, $num, $nbtotalofrecords, 'object_'.$object->picto, 0, $newcardbutton, '', $limit, 0, 0, 1);

    include DOL_DOCUMENT_ROOT.'/core/tpl/massactions_pre.tpl.php';

    $fieldstosearchall = array();
    foreach ($objectposition->fields as $key => $val) {
        if (!empty($val['searchall'])) {
            $fieldstosearchall['t.'.$key] = $val['label'];
        }
    }

    if ($search_all) {
        foreach ($fieldstosearchall as $key => $val) {
            $fieldstosearchall[$key] = $langs->trans($val);
        }
        print '<div class="divsearchfieldfilter">'.$langs->trans("FilterOnInto", $search_all).implode(', ', $fieldstosearchall).'</div>';
    }

    $arrayfields = array();
    foreach ($objectposition->fields as $key => $val) {
        if (!empty($val['visible'])) {
            $visible = (int) dol_eval((string) $val['visible'], 1, 1, '1');
            $arrayfields['t.'.$key] = array(
                'label' => $val['label'],
                'checked' => (($visible < 0) ? 0 : 1),
                'enabled' => (abs($visible) != 3 && (bool) dol_eval((string) $val['enabled'], 1)),
                'position' => $val['position'],
                'help' => isset($val['help']) ? $val['help'] : ''
            );
        }
    }
    include DOL_DOCUMENT_ROOT.'/core/tpl/extrafields_list_array_fields.tpl.php';
    $objectposition->fields = dol_sort_array($objectposition->fields, 'position');
    $arrayfields = dol_sort_array($arrayfields, 'position');

    $varpage = empty($contextpage) ? $_SERVER["PHP_SELF"] : $contextpage;
    $selectedfields = $form->multiSelectArrayWithCheckbox('selectedfields', $arrayfields, $varpage);
    $selectedfields .= (count($arrayofmassactions) ? $form->showCheckAddButtons('checkforselect', 1) : '');

    print '<div class="div-table-responsive">';
    print '<table class="tagtable nobottomiftotal liste">'."\n";

    print '<tr class="liste_titre">';
    foreach ($object->fields as $key => $val) {
        $cssforfield = (empty($val['csslist']) ? (empty($val['css']) ? '' : $val['css']) : $val['csslist']);
        if ($key == 'status') {
            $cssforfield .= ($cssforfield ? ' ' : '').'center';
        } elseif (in_array($val['type'], array('date', 'datetime', 'timestamp'))) {
            $cssforfield .= ($cssforfield ? ' ' : '').'center';
        } elseif (in_array($val['type'], array('timestamp'))) {
            $cssforfield .= ($cssforfield ? ' ' : '').'nowrap';
        } elseif (in_array($val['type'], array('double(24,8)', 'double(6,3)', 'integer', 'real', 'price')) && $val['label'] != 'TechnicalID' && empty($val['arrayofkeyval'])) {
            $cssforfield .= ($cssforfield ? ' ' : '').'right';
        }
        if (!empty($arrayfields['t.'.$key]['checked'])) {
            print getTitleFieldOfList($arrayfields['t.'.$key]['label'], 0, $_SERVER['PHP_SELF'], 't.'.$key, '', $param, ($cssforfield ? 'class="'.$cssforfield.'"' : ''), $sortfield, $sortorder, ($cssforfield ? $cssforfield.' ' : ''))."\n";
        }
    }
    include DOL_DOCUMENT_ROOT.'/core/tpl/extrafields_list_search_title.tpl.php';
    print getTitleFieldOfList($selectedfields, 0, $_SERVER["PHP_SELF"], '', '', '', '', $sortfield, $sortorder, 'center maxwidthsearch ')."\n";
    print '</tr>'."\n";

    if ($num > 0) {
        $i = 0;
        while ($i < min($num, $limit)) {
            $obj = $db->fetch_object($resql);
            if (empty($obj)) {
                break;
            }

            $object->id = $obj->rowid;
            foreach ($object->fields as $key => $val) {
                if (property_exists($obj, $key)) {
                    $object->$key = $obj->$key;
                }
            }

            print '<tr class="oddeven">';
            foreach ($object->fields as $key => $val) {
                $cssforfield = (empty($val['csslist']) ? (empty($val['css']) ? '' : $val['css']) : $val['csslist']);
                if (in_array($val['type'], array('date', 'datetime', 'timestamp'))) {
                    $cssforfield .= ($cssforfield ? ' ' : '').'center';
                } elseif ($key == 'status') {
                    $cssforfield .= ($cssforfield ? ' ' : '').'center';
                }

                if (in_array($val['type'], array('timestamp'))) {
                    $cssforfield .= ($cssforfield ? ' ' : '').'nowrap';
                } elseif ($key == 'ref') {
                    $cssforfield .= ($cssforfield ? ' ' : '').'nowrap';
                }

                if (in_array($val['type'], array('double(24,8)', 'double(6,3)', 'integer', 'real', 'price')) && !in_array($key, array('rowid', 'status')) && empty($val['arrayofkeyval'])) {
                    $cssforfield .= ($cssforfield ? ' ' : '').'right';
                }

                if (!empty($arrayfields['t.'.$key]['checked'])) {
                    print '<td'.($cssforfield ? ' class="'.$cssforfield.'"' : '').'>';
                    if ($key == 'status') {
                        print $object->getLibStatut(5);
                    } elseif ($key == 'rowid') {
                        print $object->showOutputField($val, $key, $object->id, '');
                    } else {
                        print $object->showOutputField($val, $key, $object->$key, '');
                    }
                    print '</td>';
                    if (!$i) {
                        $totalarray['nbfield']++;
                    }
                }
            }
            include DOL_DOCUMENT_ROOT.'/core/tpl/extrafields_list_print_fields.tpl.php';
            print '<td class="nowrap center">';
            if ($massactionbutton || $massaction) {
                $selected = 0;
                if (in_array($object->id, $arrayofselected)) {
                    $selected = 1;
                }
                print '<input id="cb'.$object->id.'" class="flat checkforselect" type="checkbox" name="toselect[]" value="'.$object->id.'"'.($selected ? ' checked="checked"' : '').'>';
            }
            print '</td>';
            if (!$i) {
                $totalarray['nbfield']++;
            }

            print '</tr>'."\n";

            $i++;
        }
    } else {
        print '<tr><td colspan="20"><span class="opacitymedium">'.$langs->trans("NoRecordFound").'</span></td></tr>';
    }
    print '</table>'."\n";
    print '</div>'."\n";

    print '</form>'."\n";
} else {
    print '<div class="error">'.$langs->trans("ErrorRecordNotFound").'</div>';
}

llxFooter();
$db->close();
@endphp
