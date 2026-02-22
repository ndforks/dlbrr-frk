<?php
/* Copyright (C) 2017		Laurent Destailleur			<eldy@users.sourceforge.net>
 * Copyright (C) 2024-2025  Frédéric France             <frederic.france@free.fr>
 * Copyright (C) 2024		Alexandre Spangaro			<alexandre@inovea-conseil.com>
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
 *  \file       mo_agenda.php
 *  \ingroup    mrp
 *  \brief      Page of Mo events
 */

// Load Dolibarr environment
require '../main.inc.php';

require_once DOL_DOCUMENT_ROOT.'/projet/class/project.class.php';
require_once DOL_DOCUMENT_ROOT.'/contact/class/contact.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formprojet.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/company.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT.'/mrp/class/mo.class.php';
require_once DOL_DOCUMENT_ROOT.'/mrp/lib/mrp_mo.lib.php';


/**
 * @var Conf $conf
 * @var DoliDB $db
 * @var HookManager $hookmanager
 * @var Translate $langs
 * @var User $user
 */

// Load translation files required by the page
$langs->loadLangs(array("mrp", "other"));

// Get parameters
$id 		= request()->integer('id', 0);
$ref        = request()->input('ref');
$action 	= request()->input('action');
$cancel     = request()->input('cancel');
$backtopage = request()->input('backtopage');
$contextpage = request()->input('contextpage') ? request()->input('contextpage') : 'moagenda'; // To manage different context of search

// Protection
$socid = 0;
if ($user->socid > 0) {
	$socid = $user->socid;
}

if (request()->input('actioncode')) {
	$actioncode = GETPOST('actioncode', 'array', 3);
	if (!count($actioncode)) {
		$actioncode = '0';
	}
} else {
	$actioncode = GETPOST("actioncode", "alpha", 3) ? GETPOST("actioncode", "alpha", 3) : (request()->input('actioncode') == '0' ? '0' : getDolGlobalString('AGENDA_DEFAULT_FILTER_TYPE_FOR_OBJECT'));
}
$search_rowid = request()->input('search_rowid');
$search_agenda_label = request()->input('search_agenda_label');

$limit 		= request()->integer('limit', 0) ? request()->integer('limit', 0) : $conf->liste_limit;
$sortfield	= request()->input('sortfield');
$sortorder	= request()->input('sortorder');
$page 		= request()->has('pageplusone') ? (request()->integer('pageplusone', 0) - 1) : request()->integer('page', 0);
if (empty($page) || $page == -1) {
	$page = 0;
}     // If $page is not defined, or '' or -1
$offset = $limit * $page;
$pageprev = $page - 1;
$pagenext = $page + 1;
if (!$sortfield) {
	$sortfield = 'a.datep,a.id';
}
if (!$sortorder) {
	$sortorder = 'DESC';
}

// Initialize a technical objects
$object = new Mo($db);
$extrafields = new ExtraFields($db);
$diroutputmassaction = $conf->mrp->dir_output.'/temp/massgeneration/'.$user->id;
$hookmanager->initHooks(array('moagenda', 'globalcard')); // Note that conf->hooks_modules contains array

// Fetch optionals attributes and labels
$extrafields->fetch_name_optionals_label($object->table_element);

// Load object
include DOL_DOCUMENT_ROOT.'/core/actions_fetchobject.inc.php'; // Must be 'include', not 'include_once'. Include fetch and fetch_thirdparty but not fetch_optionals
if ($id > 0 || !empty($ref)) {
	$upload_dir = (empty($conf->mrp->multidir_output[$object->entity ?? $conf->entity]) ? $conf->mrp->dir_output : $conf->mrp->multidir_output[$object->entity ?? $conf->entity])."/".$object->id;
}

// Security check - Protection if external user
//if ($user->socid > 0) abort(403);
//if ($user->socid > 0) $socid = $user->socid;
$isdraft = (($object->status == $object::STATUS_DRAFT) ? 1 : 0);
$result = restrictedArea($user, 'mrp', $object->id, 'mrp_mo', '', 'fk_soc', 'rowid', $isdraft);


/*
 *	Actions
 */

$parameters = array('id' => $socid);
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
		$search_agenda_label = '';
	}
}



/*
 *	View
 */

$contactstatic = new Contact($db);

$form = new Form($db);
$formproject = new FormProjets($db);

if ($object->id > 0) {
	$title = $langs->trans("Agenda");
	$help_url = 'EN:Module_Agenda_En|FR:Module_Agenda|ES:Módulo_Agenda|DE:|DE:Modul_Terminplanung';

	llxHeader('', $title, $help_url, '', 0, 0, '', '', '', 'mod-mrp page-card_agenda');

	if (isModEnabled('notification')) {
		$langs->load("mails");
	}
	$head = moPrepareHead($object);


	print dol_get_fiche_head($head, 'agenda', $langs->trans("ManufacturingOrder"), -1, $object->picto);

	// Object card
	// ------------------------------------------------------------
	$linkback = '<a href="'.dol_buildpath('/mrp/mo_list.php', 1).'?restore_lastsearch_values=1'.(!empty($socid) ? '&socid='.$socid : '').'">'.$langs->trans("BackToList").'</a>';

	$morehtmlref = '<div class="refidno">';
	// Ref customer
	//$morehtmlref.=$form->editfieldkey("RefCustomer", 'ref_client', $object->ref_client, $object, 0, 'string', '', 0, 1);
	//$morehtmlref.=$form->editfieldval("RefCustomer", 'ref_client', $object->ref_client, $object, 0, 'string', '', null, null, '', 1);
	// Thirdparty
	if (is_object($object->thirdparty)) {
		$morehtmlref .= $object->thirdparty->getNomUrl(1, 'customer');
		if (!getDolGlobalString('MAIN_DISABLE_OTHER_LINK') && $object->thirdparty->id > 0) {
			$morehtmlref .= ' (<a href="'.DOL_URL_ROOT.'/commande/list.php?socid='.$object->thirdparty->id.'&search_societe='.urlencode($object->thirdparty->name).'">'.$langs->trans("OtherOrders").'</a>)';
		}
	}
	// Project
	if (isModEnabled('project')) {
		$langs->load("projects");
		if (is_object($object->thirdparty)) {
			$morehtmlref .= '<br>';
		}
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

	dol_banner_tab($object, 'ref', $linkback, 1, 'ref', 'ref', $morehtmlref);

	print '<div class="fichecenter">';
	print '<div class="underbanner clearboth"></div>';

	$object->info($object->id);
	dol_print_object_info($object, 1);

	print '</div>';

	print dol_get_fiche_end();



	// Actions buttons

	$objthirdparty = $object;
	$objcon = new stdClass();

	$out = '&origin='.$object->element.'&originid='.$object->id;
	$permok = $user->hasRight('agenda', 'myactions', 'create');
	if ((!empty($objthirdparty->id) || !empty($objcon->id)) && $permok) {
		//$out.='<a href="'.DOL_URL_ROOT.'/comm/action/card.php?action=create';
		if (get_class($objthirdparty) == 'Societe') {
			$out .= '&amp;socid='.$objthirdparty->id;
		}
		$out .= (!empty($objcon->id) ? '&amp;contactid='.$objcon->id : '').'&amp;backtopage='.urlencode($_SERVER["PHP_SELF"].'?id='.$object->id);
		//$out.=$langs->trans("AddAnAction").' ';
		//$out.=img_picto($langs->trans("AddAnAction"),'filenew');
		//$out.="</a>";
	}


	print '<div class="tabsAction">';

	if (isModEnabled('agenda')) {
		if ($user->hasRight('agenda', 'myactions', 'create') || $user->hasRight('agenda', 'allactions', 'create')) {
			print '<a class="butAction" href="'.DOL_URL_ROOT.'/comm/action/card.php?action=create'.$out.'">'.$langs->trans("AddAction").'</a>';
		} else {
			print '<a class="butActionRefused classfortooltip" href="#">'.$langs->trans("AddAction").'</a>';
		}
	}

	print '</div>';

	if (isModEnabled('agenda') && ($user->hasRight('agenda', 'myactions', 'read') || $user->hasRight('agenda', 'allactions', 'read'))) {
		$param = '&id='.$object->id;
		if (!empty($socid)) {
			$param .= '&socid='.$socid;
		}
		if (!empty($contextpage) && $contextpage != $_SERVER["PHP_SELF"]) {
			$param .= '&contextpage='.urlencode($contextpage);
		}
		if ($limit > 0 && $limit != $conf->liste_limit) {
			$param .= '&limit='.((int) $limit);
		}


		//print load_fiche_titre($langs->trans("ActionsOnMo"), '', '');

		// List of all actions
		$filters = array();
		$filters['search_agenda_label'] = $search_agenda_label;
		$filters['search_rowid'] = $search_rowid;

		// TODO Replace this with same code than into list.php
		show_actions_done($conf, $langs, $db, $object, null, 0, $actioncode, '', $filters, $sortfield, $sortorder);
	}
}

// End of page
llxFooter();
$db->close();
