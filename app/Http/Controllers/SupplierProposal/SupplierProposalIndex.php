<?php

namespace App\Http\Controllers\SupplierProposal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierProposalIndex extends Controller
{
    /**
     * Handle the incoming request.
     * Displays the supplier proposal dashboard with statistics.
     */
    public function __invoke(Request $request): View
    {
        global $conf, $db, $langs, $user, $hookmanager;        require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';
        
        $langs->loadLangs(['supplier_proposal', 'companies']);
        
        if (!isModEnabled('supplier_proposal')) {
            accessforbidden('Module not enabled');
        }
        
        $hookmanager->initHooks(['suppliersproposalsindex']);
        
        $socid = GETPOSTINT('socid');
        if (!empty($user->socid) && $user->socid > 0) {
            $socid = $user->socid;
        }
        
        restrictedArea($user, 'supplier_proposal');
        
        $stats = [];
        $sql = 'SELECT COUNT(p.rowid) as nb, p.fk_statut';
        $sql .= ' FROM '.MAIN_DB_PREFIX.'societe as s';
        $sql .= ', '.MAIN_DB_PREFIX.'supplier_proposal as p';
        if (!$user->hasRight('societe', 'client', 'voir')) {
            $sql .= ', '.MAIN_DB_PREFIX.'societe_commerciaux as sc';
        }
        $sql .= ' WHERE p.fk_soc = s.rowid';
        $sql .= ' AND p.entity IN ('.getEntity('supplier_proposal').')';
        if ($user->socid) {
            $sql .= ' AND p.fk_soc = '.((int) $user->socid);
        }
        if (!$user->hasRight('societe', 'client', 'voir')) {
            $sql .= ' AND s.rowid = sc.fk_soc AND sc.fk_user = '.((int) $user->id);
        }
        $sql .= ' AND p.fk_statut IN (0,1,2,3,4)';
        $sql .= ' GROUP BY p.fk_statut';
        
        $resql = $db->query($sql);
        if ($resql) {
            while ($obj = $db->fetch_object($resql)) {
                $stats[$obj->fk_statut] = $obj->nb;
            }
            $db->free($resql);
        }
        
        $drafts = [];
        $sql = 'SELECT c.rowid, c.ref, s.nom as socname, s.rowid as socid';
        $sql .= ' FROM '.MAIN_DB_PREFIX.'supplier_proposal as c';
        $sql .= ', '.MAIN_DB_PREFIX.'societe as s';
        if (!$user->hasRight('societe', 'client', 'voir')) {
            $sql .= ', '.MAIN_DB_PREFIX.'societe_commerciaux as sc';
        }
        $sql .= ' WHERE c.fk_soc = s.rowid';
        $sql .= ' AND c.entity = '.$conf->entity;
        $sql .= ' AND c.fk_statut = 0';
        if ($socid) {
            $sql .= ' AND c.fk_soc = '.((int) $socid);
        }
        if (!$user->hasRight('societe', 'client', 'voir')) {
            $sql .= ' AND s.rowid = sc.fk_soc AND sc.fk_user = '.((int) $user->id);
        }
        
        $resql = $db->query($sql);
        if ($resql) {
            while ($obj = $db->fetch_object($resql)) {
                $drafts[] = $obj;
            }
            $db->free($resql);
        }
        
        $recent = [];
        $sql = 'SELECT c.rowid, c.ref, c.fk_statut, s.nom as socname, s.rowid as socid';
        $sql .= ', c.date_cloture as datec';
        $sql .= ' FROM '.MAIN_DB_PREFIX.'supplier_proposal as c';
        $sql .= ', '.MAIN_DB_PREFIX.'societe as s';
        if (!$user->hasRight('societe', 'client', 'voir')) {
            $sql .= ', '.MAIN_DB_PREFIX.'societe_commerciaux as sc';
        }
        $sql .= ' WHERE c.fk_soc = s.rowid';
        $sql .= ' AND c.entity = '.$conf->entity;
        if ($socid) {
            $sql .= ' AND c.fk_soc = '.((int) $socid);
        }
        if (!$user->hasRight('societe', 'client', 'voir')) {
            $sql .= ' AND s.rowid = sc.fk_soc AND sc.fk_user = '.((int) $user->id);
        }
        $sql .= ' ORDER BY c.tms DESC';
        $sql .= $db->plimit(5, 0);
        
        $resql = $db->query($sql);
        if ($resql) {
            while ($obj = $db->fetch_object($resql)) {
                $recent[] = $obj;
            }
            $db->free($resql);
        }
        
        return view('supplier_proposal.index', [
            'stats' => $stats,
            'drafts' => $drafts,
            'recent' => $recent,
        ]);
    }
}
