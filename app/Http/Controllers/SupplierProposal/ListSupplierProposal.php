<?php

namespace App\Http\Controllers\SupplierProposal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListSupplierProposal extends Controller
{
    /**
     * Handle the incoming request.
     * Displays list of supplier proposals with search/filter capabilities.
     */
    public function __invoke(Request $request): View
    {
        global $conf, $db, $langs, $user, $hookmanager;
        
        require_once DOL_DOCUMENT_ROOT.'/supplier_proposal/class/supplier_proposal.class.php';
        require_once DOL_DOCUMENT_ROOT.'/core/class/html.formother.class.php';
        
        $langs->loadLangs(['companies', 'propal', 'supplier_proposal', 'compta', 'bills', 'orders']);
        
        if (!isModEnabled('supplier_proposal')) {
            accessforbidden('Module not enabled');
        }
        
        $hookmanager->initHooks(['supplierproposallist']);
        restrictedArea($user, 'supplier_proposal');
        
        $socid = GETPOSTINT('socid');
        if (!empty($user->socid)) {
            $socid = $user->socid;
        }
        
        $limit = GETPOSTINT('limit') ?: $conf->liste_limit;
        $sortfield = GETPOST('sortfield', 'aZ09comma') ?: 'p.date_creation';
        $sortorder = GETPOST('sortorder', 'aZ09comma') ?: 'DESC';
        $page = GETPOSTINT('page') ?: 0;
        $offset = $limit * $page;
        
        $search_ref = GETPOST('search_ref', 'alpha');
        $search_company = GETPOST('search_company', 'alpha');
        $search_montant_ht = GETPOST('search_montant_ht', 'alpha');
        $search_status = GETPOST('search_status', 'intcomma');
        
        $sql = 'SELECT p.rowid, p.ref, p.fk_statut, p.total_ht, p.total_tva, p.total_ttc';
        $sql .= ', s.nom as socname, s.rowid as socid';
        $sql .= ', p.date_creation, p.tms';
        $sql .= ' FROM '.MAIN_DB_PREFIX.'supplier_proposal as p';
        $sql .= ' LEFT JOIN '.MAIN_DB_PREFIX.'societe as s ON p.fk_soc = s.rowid';
        
        if (!$user->hasRight('societe', 'client', 'voir')) {
            $sql .= ' LEFT JOIN '.MAIN_DB_PREFIX.'societe_commerciaux as sc ON s.rowid = sc.fk_soc';
            $sql .= ' WHERE sc.fk_user = '.((int) $user->id);
        } else {
            $sql .= ' WHERE 1=1';
        }
        
        $sql .= ' AND p.entity IN ('.getEntity('supplier_proposal').')';
        
        if ($socid > 0) {
            $sql .= ' AND p.fk_soc = '.((int) $socid);
        }
        if (!empty($search_ref)) {
            $sql .= natural_search('p.ref', $search_ref);
        }
        if (!empty($search_company)) {
            $sql .= natural_search('s.nom', $search_company);
        }
        if (!empty($search_montant_ht)) {
            $sql .= natural_search('p.total_ht', $search_montant_ht, 1);
        }
        if ($search_status !== '' && $search_status !== null) {
            // Normalize search_status to an array of non-negative integers (supports comma-separated string from GETPOST)
            if (is_array($search_status)) {
                $statusList = $search_status;
            } else {
                $statusList = explode(',', (string) $search_status);
            }

            $statusList = array_filter(
                array_map('trim', $statusList),
                function ($value) {
                    return $value !== '' && is_numeric($value) && (int) $value >= 0;
                }
            );

            $statusList = array_values(array_unique(array_map('intval', $statusList)));

            if (!empty($statusList)) {
                if (count($statusList) > 1) {
                    $sql .= ' AND p.fk_statut IN ('.$db->sanitize(implode(',', $statusList)).')';
                } else {
                    $sql .= ' AND p.fk_statut = '.reset($statusList);
                }
            }
        }
        
        $sql .= $db->order($sortfield, $sortorder);
        $sql .= $db->plimit($limit + 1, $offset);
        
        $resql = $db->query($sql);
        $num = 0;
        $proposals = [];
        if ($resql) {
            $num = $db->num_rows($resql);
            $i = 0;
            while ($i < min($num, $limit)) {
                $obj = $db->fetch_object($resql);
                if ($obj) {
                    $proposals[] = $obj;
                }
                $i++;
            }
            $db->free($resql);
        }
        
        return view('supplier_proposal.list', [
            'proposals' => $proposals,
            'total' => $num,
            'limit' => $limit,
            'page' => $page,
            'sortfield' => $sortfield,
            'sortorder' => $sortorder,
            'search_ref' => $search_ref,
            'search_company' => $search_company,
            'search_montant_ht' => $search_montant_ht,
            'search_status' => $search_status,
        ]);
    }
}
