<?php
/* Copyright (C) 2004-2009	Laurent Destailleur			<eldy@users.sourceforge.net>
 * Copyright (C) 2017		Ferran Marcet				<fmarcet@2byte.es>
 * Copyright (C) 2024		Alexandre Spangaro			<alexandre@inovea-conseil.com>
 * Copyright (C) 2024-2025  Frédéric France         <frederic.france@free.fr>
 * Copyright (C) 2024-2025	MDW							<mdeweerd@users.noreply.github.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

/**
 *      \file       htdocs/contrat/agenda.php
 *      \ingroup    contrat
 *      \brief      Page of contract events
 */

require "../main.inc.php";
require_once DOL_DOCUMENT_ROOT.'/Core/lib/company.lib.php';
require_once DOL_DOCUMENT_ROOT.'/Core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT.'/Core/lib/contract.lib.php';
require_once DOL_DOCUMENT_ROOT.'/Core/class/html.formfile.class.php';
require_once DOL_DOCUMENT_ROOT.'/contrat/class/contrat.class.php';
if (isModEnabled('project')) {
	require_once DOL_DOCUMENT_ROOT.'/projet/class/project.class.php';
	require_once DOL_DOCUMENT_ROOT.'/Core/class/html.formprojet.class.php';
}

/**
 * @var Conf $conf
 * @var DoliDB $db
 * @var HookManager $hookmanager
 * @var Translate $langs
 * @var User $user
 */

// Load translation files required by the page
$langs->loadLangs(array("companies", "contracts"));

$action		= request()->input('action');
$confirm	= request()->input('confirm');
$contextpage = request()->input('contextpage') ? request()->input('contextpage') : 'contratagenda';

if (request()->input('actioncode')) {
	$actioncode = request()->input('actioncode', []);
	if (!count($actioncode)) {
		$actioncode = '0';
	}
} else {
	$actioncode = request()->input('actioncode') ? request()->input('actioncode') : (request()->input('actioncode') == '0' ? '0' : getDolGlobalString('AGENDA_DEFAULT_FILTER_TYPE_FOR_OBJECT'));
}

$search_rowid = request()->integer('search_rowid', 0);
$search_agenda_label = request()->input('search_agenda_label');
$search_complete = request()->input('search_complete');
$search_filtert = request()->integer('search_filtert', 0);
$search_dateevent_start = GETPOSTDATE('dateevent_start');
$search_dateevent_end = GETPOSTDATE('dateevent_end');

$id = request()->integer('id', 0);
$ref = request()->input('ref');

// Security check
if ($user->socid) {
	$socid = $user->socid;
}

// Security check
$fieldvalue = (!empty($id) ? $id : (!empty($ref) ? $ref : ''));
$fieldtype = (!empty($id) ? 'rowid' : 'ref');

// Initialize a technical object to manage hooks of page. Note that conf->hooks_modules contains an array of hook context
$hookmanager->initHooks(array('agendacontract', 'globalcard'));

$result = restrictedArea($user, 'contrat', $fieldvalue, '', '', '', $fieldtype);

$limit = request()->integer('limit', 0) ? request()->integer('limit', 0) : $conf->liste_limit;
$sortfield = request()->input('sortfield');
$sortorder = request()->input('sortorder');
$page = request()->has('pageplusone') ? (request()->integer('pageplusone', 0) - 1) : request()->integer('page', 0);
if (empty($page) || $page < 0 || request()->input('button_search') || request()->input('button_removefilter')) {
	// If $page is not defined, or '' or -1 or if we click on clear filters
	$page = 0;
}
$offset = $limit * $page;
$pageprev = $page - 1;
$pagenext = $page + 1;
if (!$sortfield) {
	$sortfield = 'a.datep,a.id';
}
if (!$sortorder) {
	$sortorder = 'DESC,DESC';
}


$object = new Contrat($db);
if ($id > 0 || !empty($ref)) {
	$result = $object->fetch($id, $ref);
}

$permissiontoadd = $user->hasRight('contrat', 'creer');     //  Used by the include of actions_addupdatedelete.inc.php and actions_lineupdown.inc.php

$result = restrictedArea($user, 'contrat', $object->id);


/*
 * Actions
 */

$parameters = array('id' => $id, 'ref' => $ref);
$reshook = $hookmanager->executeHooks('doActions', $parameters, $object, $action); // Note that $action and $object may have been modified by some hooks
if ($reshook < 0) {
	setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');
}

if (empty($reshook)) {
	// Cancel
	if (request()->input('cancel') && !empty($backtopage)) {
		header("Location: ".$backtopage);
		exit;
	}

	// Purge search criteria
	if (request()->input('button_removefilter_x') || request()->input('button_removefilter.x') || request()->input('button_removefilter')) { // All tests are required to be compatible with all browsers
		$actioncode = '';
		$search_rowid = '';
		$search_agenda_label = '';
		$search_complete = '';
		$search_filtert = '';
	}
}


/*
 * View
 */

$form = new Form($db);
$formfile = new FormFile($db);
if (isModEnabled('project')) {
	$formproject = new FormProjets($db);
}

if ($object->id > 0) {
	// Load object modContract
	$module = getDolGlobalString('CONTRACT_ADDON', 'mod_contract_serpis');
	if (substr($module, 0, 13) == 'mod_contract_' && substr($module, -3) == 'php') {
		$module = substr($module, 0, dol_strlen($module) - 4);
	}
	$result = dol_include_once('/core/modules/contract/'.$module.'.php');
	if ($result > 0) {
		$modCodeContract = new $module();
	}

	require_once DOL_DOCUMENT_ROOT.'/Core/lib/company.lib.php';
	require_once DOL_DOCUMENT_ROOT.'/contrat/class/contrat.class.php';

	$object->fetch_thirdparty();

	$title = $langs->trans("Agenda");
	if (getDolGlobalString('MAIN_HTML_TITLE') && preg_match('/contractrefonly/', getDolGlobalString('MAIN_HTML_TITLE')) && $object->ref) {
		$title = $object->ref." - ".$title;
	}
	$help_url = 'EN:Module_Contracts|FR:Module_Contrat|ES:Contratos_de_servicio';

	llxHeader('', $title, $help_url, '', 0, 0, '', '', '', 'mod-contrat page-card_agenda');

	if (isModEnabled('notification')) {
		$langs->load("mails");
	}
	$head = contract_prepare_head($object);

	print dol_get_fiche_head($head, 'agenda', $langs->trans("Contract"), -1, 'contract');

	$linkback = '<a href="'.DOL_URL_ROOT.'/contrat/list.php?restore_lastsearch_values=1">'.$langs->trans("BackToList").'</a>';

	$morehtmlref = '';
	if (!empty($modCodeContract->code_auto)) {
		$morehtmlref .= $object->ref;
	} else {
		$morehtmlref .= $form->editfieldkey("", 'ref', $object->ref, $object, $user->hasRight('contrat', 'creer'), 'string', '', 0, 3);
		$morehtmlref .= $form->editfieldval("", 'ref', $object->ref, $object, $user->hasRight('contrat', 'creer'), 'string', null, null, '2');
	}

	$permtoedit = 0;

	$morehtmlref .= '<div class="refidno">';
	// Ref customer
	$morehtmlref .= $form->editfieldkey("RefCustomer", 'ref_customer', $object->ref_customer, $object, $permtoedit, 'string', '', 0, 1);
	$morehtmlref .= $form->editfieldval("RefCustomer", 'ref_customer', $object->ref_customer, $object, $permtoedit, 'string', '', null, null, '', 1, 'getFormatedCustomerRef');
	// Ref supplier
	$morehtmlref .= '<br>';
	$morehtmlref .= $form->editfieldkey("RefSupplier", 'ref_supplier', $object->ref_supplier, $object, $permtoedit, 'string', '', 0, 1);
	$morehtmlref .= $form->editfieldval("RefSupplier", 'ref_supplier', $object->ref_supplier, $object, $permtoedit, 'string', '', null, null, '', 1, 'getFormatedSupplierRef');
	// Thirdparty
	$morehtmlref .= '<br>'.$object->thirdparty->getNomUrl(1);
	if (!getDolGlobalString('MAIN_DISABLE_OTHER_LINK') && $object->thirdparty->id > 0) {
		$morehtmlref .= ' <span class="otherlink valignmiddle">(<a href="'.dolBuildUrl(DOL_URL_ROOT.'/contrat/list.php', ['socid' => $object->thirdparty->id, 'search_name' => $object->thirdparty->name]).'">'.$langs->trans("OtherContracts").'</a>)</span>';
	}
	// Project
	if (isModEnabled('project')) {
		$langs->load("projects");
		$morehtmlref .= '<br>';
		if (0) {	// @phpstan-ignore-line
			$morehtmlref .= img_picto($langs->trans("Project"), 'project', 'class="pictofixedwidth"');
			if ($action != 'classify') {
				$morehtmlref .= '<a class="editfielda" href="'.dolBuildUrl($_SERVER['PHP_SELF'], ['action' => 'classify', 'id' => $object->id], true).'">'.img_edit($langs->transnoentitiesnoconv('SetProject')).'</a> ';
			}
			$morehtmlref .= $form->form_project($_SERVER['PHP_SELF'].'?id='.$object->id, $object->socid, (string) $object->fk_project, ($action == 'classify' ? 'projectid' : 'none'), 0, 0, 0, 1, '', 'maxwidth300');
		} else {
			if (!empty($object->fk_project)) {
				$proj = new Project($db);
				$proj->fetch($object->fk_project);
				$morehtmlref .= $proj->getNomUrl(1);
				if ($proj->title) {
					$morehtmlref .= '<span class="opacitymedium"> - '.dol_escape_htmltag($proj->title).'</span>';
				}
			}
		}
	}
	$morehtmlref .= '</div>';

	dol_banner_tab($object, 'ref', $linkback, 1, 'ref', 'none', $morehtmlref);

	print '<div class="fichecenter">';

	print '<div class="underbanner clearboth"></div>';

	$object->info($object->id);
	dol_print_object_info($object, 1);

	print '</div>';

	print dol_get_fiche_end();


	// Actions buttons

	/*$objthirdparty=$object;
	$objcon=new stdClass();

	$out='';
	$permok=$user->rights->agenda->myactions->create;
	if ((!empty($objthirdparty->id) || !empty($objcon->id)) && $permok)
	{
		//$out.='<a href="'.DOL_URL_ROOT.'/comm/action/card.php?action=create';
		if (get_class($objthirdparty) == 'Societe') $out.='&amp;socid='.$objthirdparty->id;
		$out.=(!empty($objcon->id)?'&amp;contactid='.$objcon->id:'').'&amp;backtopage=1';
		//$out.=$langs->trans("AddAnAction").' ';
		//$out.=img_picto($langs->trans("AddAnAction"),'filenew');
		//$out.="</a>";
	}*/


	//print '<div class="tabsAction">';
	//print '</div>';


	$newcardbutton = '';
	if (isModEnabled('agenda')) {
		if ($user->hasRight('agenda', 'myactions', 'create') || $user->hasRight('agenda', 'allactions', 'create')) {
			$backtopage = $_SERVER['PHP_SELF'].'?id='.$object->id;
			$messagingUrl = dolBuildUrl(DOL_URL_ROOT.'/contrat/messaging.php', ['id' => $object->id]);
			$newcardbutton .= dolGetButtonTitle($langs->trans('ShowAsConversation'), '', 'fa fa-comments imgforviewmode', $messagingUrl, '', 1);
			$messagingUrl = dolBuildUrl(DOL_URL_ROOT.'/contrat/agenda.php', ['id' => $object->id]);
			$newcardbutton .= dolGetButtonTitle($langs->trans('MessageListViewType'), '', 'fa fa-bars imgforviewmode', $messagingUrl, '', 2);
			$query = [
				'action' => 'create',
				'origin' => $object->element,
				'originid' => $object->id,
				'backtopage' => $backtopage,
			];
			$newcardbutton .= dolGetButtonTitle($langs->trans('AddAction'), '', 'fa fa-plus-circle', dolBuildUrl(DOL_URL_ROOT.'/comm/action/card.php', $query));
		}
	}

	if (isModEnabled('agenda') && ($user->hasRight('agenda', 'myactions', 'read') || $user->hasRight('agenda', 'allactions', 'read'))) {
		print '<br>';

		$param = '&id='.$object->id;
		if (!empty($contextpage) && $contextpage != $_SERVER["PHP_SELF"]) {
			$param .= '&contextpage='.urlencode($contextpage);
		}
		if ($limit > 0 && $limit != $conf->liste_limit) {
			$param .= '&limit='.((int) $limit);
		}
		if ($search_rowid) {
			$param .= '&search_rowid='.urlencode($search_rowid);
		}
		if ($actioncode !== '' && $actioncode !== '-1') {
			$param .= '&actioncode='.urlencode($actioncode);
		}
		if ($search_agenda_label) {
			$param .= '&search_agenda_label='.urlencode($search_agenda_label);
		}
		if ($search_complete != '') {
			$param .= '&search_complete='.urlencode($search_complete);
		}
		if ($search_filtert != '') {
			$param .= '&search_filtert='.urlencode((string) $search_filtert);
		}
		if ($search_dateevent_start != '') {
			$param .= '&dateevent_startyear='.request()->integer('dateevent_startyear', 0);
			$param .= '&dateevent_startmonth='.request()->integer('dateevent_startmonth', 0);
			$param .= '&dateevent_startday='.request()->integer('dateevent_startday', 0);
		}
		if ($search_dateevent_end != '') {
			$param .= '&dateevent_endyear='.request()->integer('dateevent_endyear', 0);
			$param .= '&dateevent_endmonth='.request()->integer('dateevent_endmonth', 0);
			$param .= '&dateevent_endday='.request()->integer('dateevent_endday', 0);
		}

		// Try to know count of actioncomm from cache
		require_once DOL_DOCUMENT_ROOT.'/Core/lib/memory.lib.php';
		$cachekey = 'count_events_thirdparty_'.$object->id;
		$nbEvent = dol_getcache($cachekey);

		$titlelist = $langs->trans("ActionsOnContract").(is_numeric($nbEvent) ? '<span class="opacitymedium colorblack paddingleft">('.$nbEvent.')</span>' : '');
		if (!empty($conf->dol_optimize_smallscreen)) {
			$titlelist = $langs->trans("Actions").(is_numeric($nbEvent) ? '<span class="opacitymedium colorblack paddingleft">('.$nbEvent.')</span>' : '');
		}

		print_barre_liste($titlelist, 0, $_SERVER["PHP_SELF"], $param, $sortfield, $sortorder, '', 0, -1, '', 0, $newcardbutton, '', 0, 1, 0);

		// List of all actions
		$filters = array();
		$filters['search_agenda_label'] = $search_agenda_label;
		$filters['search_rowid'] = $search_rowid;
		$filters['search_complete'] = $search_complete;		// Can be 'na', '0', '100', '50'
		$filters['search_filtert'] = $search_filtert;

		// TODO Replace this with the same code than into list.php
		show_actions_done($conf, $langs, $db, $object, null, 0, $actioncode, '', $filters, $sortfield, $sortorder);
	}
}

llxFooter();
$db->close();
