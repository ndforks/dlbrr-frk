<?php
/* Copyright (C) 2007-2017  Laurent Destailleur     <eldy@users.sourceforge.net>
 * Copyright (C) 2019-2025  Frédéric France         <frederic.france@free.fr>
 * Copyright (C) 2024		MDW							<mdeweerd@users.noreply.github.com>
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
 *    \file       htdocs/bom/bom_document.php
 *    \ingroup    bom
 *    \brief      Tab for documents linked to BillOfMaterials
 */

// Load Dolibarr environment
require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/Core/class/html.formfile.class.php';
require_once DOL_DOCUMENT_ROOT.'/Core/lib/company.lib.php';
require_once DOL_DOCUMENT_ROOT.'/Core/lib/files.lib.php';
require_once DOL_DOCUMENT_ROOT.'/Core/lib/images.lib.php';
require_once DOL_DOCUMENT_ROOT.'/bom/class/bom.class.php';
require_once DOL_DOCUMENT_ROOT.'/bom/lib/bom.lib.php';

/**
 * @var Conf $conf
 * @var DoliDB $db
 * @var HookManager $hookmanager
 * @var Translate $langs
 * @var User $user
 */

// Load translation files required by the page
$langs->loadLangs(array("mrp", "companies", "other", "mails"));

// Get parameters
$action = request()->input('action');
$confirm = request()->input('confirm');
$id = (request()->integer('socid', 0) ? request()->integer('socid', 0) : request()->integer('id', 0));
$ref = request()->input('ref');

// Security check - Protection if external user
// if ($user->socid > 0) abort(403);
// if ($user->socid > 0) $socid = $user->socid;
// restrictedArea($user, 'bom', $id);

// Load variables for pagination
$limit = request()->integer('limit', 0) ? request()->integer('limit', 0) : $conf->liste_limit;
$sortfield = request()->input('sortfield');
$sortorder = request()->input('sortorder');
$page = request()->has('pageplusone') ? (request()->integer('pageplusone', 0) - 1) : request()->integer('page', 0);
if (empty($page) || $page == -1) {
	$page = 0;
}     // If $page is not defined, or '' or -1
$offset = $limit * $page;
$pageprev = $page - 1;
$pagenext = $page + 1;
if (!$sortorder) {
	$sortorder = "ASC";
}
if (!$sortfield) {
	$sortfield = "name";
}
//if (! $sortfield) $sortfield="position_name";

// Initialize a technical objects
$object = new BOM($db);
$extrafields = new ExtraFields($db);
$diroutputmassaction = getMultidirOutput($object) . '/temp/massgeneration/'.$user->id;
$hookmanager->initHooks(array('bomdocument', 'globalcard')); // Note that conf->hooks_modules contains array
// Fetch optionals attributes and labels
$extrafields->fetch_name_optionals_label($object->table_element);

// Load object
include DOL_DOCUMENT_ROOT.'/Core/actions_fetchobject.inc.php'; // Must be 'include', not 'include_once'. Include fetch and fetch_thirdparty but not fetch_optionals

$upload_dir = null;
if ($id > 0 || !empty($ref)) {
	$upload_dir = $conf->bom->multidir_output[$object->entity ? $object->entity : 1]."/".get_exdir(0, 0, 0, 1, $object);
}

// Security check - Protection if external user
//if ($user->socid > 0) abort(403);
//if ($user->socid > 0) $socid = $user->socid;
$isdraft = (($object->status == $object::STATUS_DRAFT) ? 1 : 0);
restrictedArea($user, 'bom', $object->id, $object->table_element, '', '', 'rowid', $isdraft);

$permissiontoadd = $user->hasRight('bom', 'write'); // Used by the include of actions_addupdatedelete.inc.php and actions_linkedfiles.inc.php


/*
 * Actions
 */

include DOL_DOCUMENT_ROOT.'/Core/actions_linkedfiles.inc.php';


/*
 * View
 */

$form = new Form($db);

$title = $langs->trans("BillOfMaterials").' - '.$langs->trans("Files");

$help_url = 'EN:Module_BOM';
$morehtmlref = "";

llxHeader('', $title, $help_url, '', 0, 0, '', '', '', 'mod-bom page-card_documents');

if ($object->id && $upload_dir !== null) {
	/*
	 * Show tabs
	 */
	$head = bomPrepareHead($object);

	print dol_get_fiche_head($head, 'document', $langs->trans("BillOfMaterials"), -1, $object->picto);


	// Build file list
	$filearray = dol_dir_list($upload_dir, "files", 0, '', '(\.meta|_preview.*\.png)$', $sortfield, (strtolower($sortorder) == 'desc' ? SORT_DESC : SORT_ASC), 1);
	$totalsize = 0;
	foreach ($filearray as $key => $file) {
		$totalsize += $file['size'];
	}

	// Object card
	// ------------------------------------------------------------
	$linkback = '<a href="'.DOL_URL_ROOT.'/bom/bom_list.php?restore_lastsearch_values=1'.(!empty($socid) ? '&socid='.$socid : '').'">'.$langs->trans("BackToList").'</a>';

	dol_banner_tab($object, 'ref', $linkback, 1, 'ref', 'ref', $morehtmlref);

	print '<div class="fichecenter">';

	print '<div class="underbanner clearboth"></div>';
	print '<table class="border centpercent tableforfield">';

	// Number of files
	print '<tr><td class="titlefield">'.$langs->trans("NbOfAttachedFiles").'</td><td colspan="3">'.count($filearray).'</td></tr>';

	// Total size
	print '<tr><td>'.$langs->trans("TotalSizeOfAttachedFiles").'</td><td colspan="3">'.$totalsize.' '.$langs->trans("bytes").'</td></tr>';

	print '</table>';

	print '</div>';

	print dol_get_fiche_end();

	$modulepart = 'bom';
	$permissiontoadd = $user->hasRight('bom', 'write');
	$permtoedit = $user->hasRight('bom', 'write');
	$param = '&id='.$object->id;

	//$relativepathwithnofile='bom/' . dol_sanitizeFileName($object->id).'/';
	$relativepathwithnofile = dol_sanitizeFileName($object->ref).'/';

	include DOL_DOCUMENT_ROOT.'/Core/tpl/document_actions_post_headers.tpl.php';
} else {
	abort(403);
}

// End of page
llxFooter();
$db->close();
