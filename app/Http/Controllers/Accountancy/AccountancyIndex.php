<?php

namespace App\Http\Controllers\Accountancy;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountancyIndex extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $db, $langs, $user, $conf, $hookmanager, $mysoc;
        
        $action = GETPOST('action', 'alpha') ?: 'view';
        
        return match($action) {
            'addbox' => $this->addBox($request),
            default => $this->show($request),
        };
    }
    
    private function addBox(Request $request): RedirectResponse
    {
        global $db, $langs;
        
        require_once DOL_DOCUMENT_ROOT.'/core/class/infobox.class.php';
        
        $zone = GETPOSTINT('areacode');
        $userid = GETPOSTINT('userid');
        $boxorder = GETPOST('boxorder', 'aZ09');
        $boxorder .= GETPOST('boxcombo', 'aZ09');
        
        $result = \InfoBox::saveboxorder($db, $zone, $boxorder, $userid);
        if ($result > 0) {
            setEventMessages($langs->trans("BoxAdded"), null);
        }
        
        return redirect('/accountancy/');
    }
    
    private function show(Request $request): View
    {
        global $db, $langs, $user, $conf, $hookmanager, $mysoc;
        
        $langs->loadLangs(array("compta", "bills", "other", "accountancy", "loans", "banks", "admin", "dict"));
        
        $hookmanager->initHooks(array('accountancyindex'));
        
        if ($user->socid > 0) {
            accessforbidden();
        }
        if (!isModEnabled('comptabilite') && !isModEnabled('accounting') && !isModEnabled('asset') && !isModEnabled('intracommreport')) {
            accessforbidden();
        }
        if (!$user->hasRight('compta', 'resultat', 'lire') && !$user->hasRight('accounting', 'comptarapport', 'lire') && !$user->hasRight('accounting', 'mouvements', 'lire') && !$user->hasRight('asset', 'read') && !$user->hasRight('intracommreport', 'read')) {
            accessforbidden();
        }
        
        $pcgver = getDolGlobalInt('CHARTOFACCOUNTS');
        
        require_once DOL_DOCUMENT_ROOT.'/core/class/html.formother.class.php';
        
        $resultboxes = \FormOther::getBoxesArea($user, "27");
        
        $boxlist = '';
        $boxlist .= '<div class="twocolumns">';
        $boxlist .= '<div class="firstcolumn fichehalfleft boxhalfleft" id="boxhalfleft">';
        $boxlist .= $resultboxes['boxlista'];
        $boxlist .= '</div>';
        $boxlist .= '<div class="secondcolumn fichehalfright boxhalfright" id="boxhalfright">';
        $boxlist .= $resultboxes['boxlistb'];
        $boxlist .= '</div>';
        $boxlist .= "\n";
        $boxlist .= '</div>';
        
        $helpisexpanded = GETPOSTINT('showtuto');
        $step = 0;
        
        $pcgversion = '';
        $pcglabel = '';
        
        if ($pcgver > 0) {
            $sql = "SELECT a.rowid, a.pcg_version, a.label, a.active";
            $sql .= " FROM ".MAIN_DB_PREFIX."accounting_system as a";
            $sql .= " WHERE a.rowid = ".((int) $pcgver);
            
            $resqlchart = $db->query($sql);
            if ($resqlchart) {
                $obj = $db->fetch_object($resqlchart);
                if ($obj) {
                    $pcgversion = $obj->pcg_version;
                    $pcglabel = $obj->label;
                }
            } else {
                dol_print_error($db);
            }
        }
        
        return view('accountancy.index', [
            'boxlist' => $boxlist,
            'resultboxes' => $resultboxes,
            'helpisexpanded' => $helpisexpanded,
            'pcgver' => $pcgver,
            'pcgversion' => $pcgversion,
            'pcglabel' => $pcglabel,
            'step' => $step,
        ]);
    }
}
