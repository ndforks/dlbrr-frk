<?php
/* Copyright (C) 2018       Alexandre Spangaro      <aspangaro@open-dsi.fr>
 * Copyright (C) 2024-2025  Frédéric France			<frederic.france@free.fr>
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
 *	\file       htdocs/admin/subcontractors.php
 *	\ingroup    core
 *	\brief      Setup page to configure subcontractors like accountantint provider / IT service provider...
 */

// Load Dolibarr environment
require '../main.inc.php';
/**
 * @var Conf $conf
 * @var DoliDB $db
 * @var HookManager $hookmanager
 * @var Translate $langs
 * @var User $user
 * @var Societe $mysoc
 */
require_once DOL_DOCUMENT_ROOT.'/Core/lib/admin.lib.php';
require_once DOL_DOCUMENT_ROOT.'/Core/lib/company.lib.php';
require_once DOL_DOCUMENT_ROOT.'/Core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT.'/Core/class/html.formother.class.php';
require_once DOL_DOCUMENT_ROOT.'/Core/class/html.formcompany.class.php';

$action = request()->input('action');
$contextpage = request()->input('contextpage') ? request()->input('contextpage') : 'adminsubcontractors'; // To manage different context of search

// Load translation files required by the page
$langs->loadLangs(array('admin', 'companies'));

if (!$user->admin) {
	abort(403);
}

$object = new stdClass();


/*
 * Actions
 */

$parameters = array();
$reshook = $hookmanager->executeHooks('doActions', $parameters, $object, $action); 	// Note that $action and $object may have been modified by some hooks
if ($reshook < 0) {
	setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');
}

if (($action == 'update' && !request()->input('cancel')) || ($action == 'updateedit')) {
	dolibarr_set_const($db, "MAIN_INFO_ACCOUNTANT_NAME", request()->input('nom'), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ACCOUNTANT_ADDRESS", request()->input('address'), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ACCOUNTANT_TOWN", request()->input('town'), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ACCOUNTANT_ZIP", request()->input('zipcode'), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ACCOUNTANT_STATE", request()->integer('state_id', 0), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ACCOUNTANT_REGION", request()->input('region_code'), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ACCOUNTANT_COUNTRY", request()->integer('country_id', 0), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ACCOUNTANT_PHONE", request()->input('phone'), 'chaine', 0, '', $conf->entity);
	//dolibarr_set_const($db, "MAIN_INFO_ACCOUNTANT_FAX", request()->input('fax'), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ACCOUNTANT_MAIL", request()->input('mail'), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ACCOUNTANT_WEB", request()->input('web'), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ACCOUNTANT_IDPROF1", request()->input('idprof1'), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ACCOUNTANT_CODE", request()->input('code'), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ACCOUNTANT_NOTE", request()->input('note'), 'chaine', 0, '', $conf->entity);

	dolibarr_set_const($db, "MAIN_INFO_ITPROVIDER_NAME", request()->input('itprovider_nom'), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ITPROVIDER_ADDRESS", request()->input('itprovider_address'), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ITPROVIDER_TOWN", request()->input('itprovider_town'), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ITPROVIDER_ZIP", request()->input('itprovider_zipcode'), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ITPROVIDER_STATE", request()->integer('itprovider_state_id', 0), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ITPROVIDER_REGION", request()->input('itprovider_region_code'), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ITPROVIDER_COUNTRY", request()->integer('itprovider_country_id', 0), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ITPROVIDER_PHONE", request()->input('itprovider_phone'), 'chaine', 0, '', $conf->entity);
	//dolibarr_set_const($db, "MAIN_INFO_ITPROVIDER_FAX", request()->input('itprovider_fax'), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ITPROVIDER_MAIL", request()->input('itprovider_mail'), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ITPROVIDER_WEB", request()->input('itprovider_web'), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ITPROVIDER_IDPROF1", request()->input('itprovider_idprof1'), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ITPROVIDER_CODE", request()->input('itprovider_code'), 'chaine', 0, '', $conf->entity);
	dolibarr_set_const($db, "MAIN_INFO_ITPROVIDER_NOTE", request()->input('itprovider_note'), 'chaine', 0, '', $conf->entity);

	if ($action != 'updateedit') {
		setEventMessages($langs->trans("SetupSaved"), null, 'mesgs');
	}
}


/*
 * View
 */

$help_url = '';
llxHeader('', $langs->trans("CompanyFoundation"), $help_url, '', 0, 0, '', '', '', 'mod-admin page-subcontractors');

print load_fiche_titre($langs->trans("CompanyFoundation"), '', 'title_setup');

$head = company_admin_prepare_head();

print dol_get_fiche_head($head, 'subcontractors', '', -1, '');

$form = new Form($db);
$formother = new FormOther($db);
$formcompany = new FormCompany($db);

$countrynotdefined = '<span class="error">'.$langs->trans("ErrorSetACountryFirst").' ('.$langs->trans("SeeAbove").')</span>';

print '<span class="opacitymedium">'.$langs->trans("SubcontractorsDesc")."</span><br>\n";
print "<br><br>\n";

/**
 * Edit parameters
 */
if (!empty($conf->use_javascript_ajax)) {
	print "\n".'<script type="text/javascript">';
	print '$(document).ready(function () {
		  $("#selectcountry_id").change(function() {
			console.log("selectcountry_id change");
			document.form_index.action.value="updateedit";
			document.form_index.submit();
		  });
		  $("#selectitprovider_country_id").change(function() {
			console.log("selectitprovider_country_id change");
			document.form_index.action.value="updateedit";
			document.form_index.submit();
		  });
	  });';
	print '</script>'."\n";
}

print '<form method="POST" action="'.dolBuildUrl($_SERVER["PHP_SELF"]).'" name="form_index">';
print '<input type="hidden" name="token" value="'.newToken().'">';
print '<input type="hidden" name="action" value="update">';
print '<input type="hidden" name="page_y" value="">';

print load_fiche_titre($langs->trans("AccountingProvider"), '', 'accounting', 0, '', '', '', '');

print '<table class="noborder centpercent editmode">';
print '<tr class="liste_titre"><th class="titlefieldcreate wordbreak">'.$langs->trans("CompanyInfo").'</th><th></th></tr>'."\n";

// Name of Accountant Company
print '<tr class="oddeven"><td><label for="name">'.$langs->trans("CompanyName").'</label></td><td>';
print '<input name="nom" id="name" class="minwidth200" value="'.dol_escape_htmltag(request()->has('nom') ? request()->input('nom') : getDolGlobalString('MAIN_INFO_ACCOUNTANT_NAME')).'"'.(!getDolGlobalString('MAIN_INFO_ACCOUNTANT_NAME') ? ' autofocus="autofocus"' : '').'></td></tr>'."\n";

// Address
print '<tr class="oddeven"><td><label for="address">'.$langs->trans("CompanyAddress").'</label></td><td>';
print '<textarea name="address" id="address" class="quatrevingtpercent" rows="'.ROWS_2.'">';
print dolPrintText(request()->has('address') ? request()->input('address') : getDolGlobalString('MAIN_INFO_ACCOUNTANT_ADDRESS'));
print '</textarea></td></tr>'."\n";

// ZIP
print '<tr class="oddeven"><td><label for="zipcode">'.$langs->trans("CompanyZip").'</label></td><td>';
print '<input class="width100" name="zipcode" id="zipcode" value="'.dol_escape_htmltag(request()->has('zipcode') ? request()->input('zipcode') : getDolGlobalString('MAIN_INFO_ACCOUNTANT_ZIP')).'"></td></tr>'."\n";

// Town/City
print '<tr class="oddeven"><td><label for="town">'.$langs->trans("CompanyTown").'</label></td><td>';
print '<input name="town" class="minwidth100" id="town" value="'.dol_escape_htmltag(request()->has('town') ? request()->input('town') : getDolGlobalString('MAIN_INFO_ACCOUNTANT_TOWN')).'"></td></tr>'."\n";

// Country
print '<tr class="oddeven"><td><label for="selectcountry_id">'.$langs->trans("Country").'</label></td><td class="maxwidthonsmartphone">';
print img_picto('', 'globe-americas', 'class="pictofixedwidth"');
print $form->select_country((request()->has('country_id') ? request()->integer('country_id', 0) : getDolGlobalString('MAIN_INFO_ACCOUNTANT_COUNTRY')), 'country_id');
print info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionarySetup"), 1);
print '</td></tr>'."\n";

// State
print '<tr class="oddeven"><td><label for="state_id">'.$langs->trans("State").'</label></td><td class="maxwidthonsmartphone">';
print img_picto('', 'state', 'class="pictofixedwidth"');
print $formcompany->select_state((request()->has('state_id') ? request()->integer('state_id', 0) : getDolGlobalString('MAIN_INFO_ACCOUNTANT_STATE')), (request()->has('country_id') ? request()->integer('country_id', 0) : getDolGlobalString('MAIN_INFO_ACCOUNTANT_COUNTRY')), 'state_id');
print '</td></tr>'."\n";

// Telephone
print '<tr class="oddeven"><td><label for="phone">'.$langs->trans("Phone").'</label></td><td>';
print img_picto('', 'object_phoning', '', 0, 0, 0, '', 'pictofixedwidth');
print '<input name="phone" id="phone" class="maxwidth150 widthcentpercentminusx" value="'.dol_escape_htmltag(request()->has('phone') ? request()->input('phone') : getDolGlobalString('MAIN_INFO_ACCOUNTANT_PHONE')).'"></td></tr>';
print '</td></tr>'."\n";

// Fax
/*
print '<tr class="oddeven"><td><label for="fax">'.$langs->trans("Fax").'</label></td><td>';
print img_picto('', 'object_phoning_fax', '', 0, 0, 0, '', 'pictofixedwidth');
print '<input name="fax" id="fax" class="maxwidth150 widthcentpercentminusx" value="'.dol_escape_htmltag(request()->has('fax') ? request()->input('fax') : getDolGlobalString('MAIN_INFO_ACCOUNTANT_FAX')).'"></td></tr>';
print '</td></tr>'."\n";
*/

// eMail
print '<tr class="oddeven"><td><label for="email">'.$langs->trans("EMail").'</label></td><td>';
print img_picto('', 'object_email', '', 0, 0, 0, '', 'pictofixedwidth');
print '<input name="mail" id="email" class="maxwidth300 widthcentpercentminusx" value="'.dol_escape_htmltag(request()->has('mail') ? request()->input('mail') : getDolGlobalString('MAIN_INFO_ACCOUNTANT_MAIL')).'"></td></tr>';
print '</td></tr>'."\n";

// Web
print '<tr class="oddeven"><td><label for="web">'.$langs->trans("Web").'</label></td><td>';
print img_picto('', 'globe', '', 0, 0, 0, '', 'pictofixedwidth');
print '<input name="web" id="web" class="maxwidth300 widthcentpercentminusx" value="'.dol_escape_htmltag(request()->has('web') ? request()->input('web') : getDolGlobalString('MAIN_INFO_ACCOUNTANT_WEB')).'"></td></tr>';
print '</td></tr>'."\n";

// Id prof
print '<tr class="oddeven"><td><label for="idprof1">'.$langs->transcountry("ProfId1", $mysoc->country_code).'</label></td><td>';
print '<input name="idprof1" id="idprof1" class="minwidth100" value="'.dol_escape_htmltag(request()->has('idprof1') ? request()->input('idprof1') : getDolGlobalString('MAIN_INFO_ACCOUNTANT_IDPROF1')).'"></td></tr>'."\n";

// Code
print '<tr class="oddeven"><td><label for="code">'.$langs->trans("AccountantFileNumber").'</label></td><td>';
print '<input name="code" id="code" class="minwidth100" value="'.dol_escape_htmltag(request()->has('code') ? request()->input('code') : getDolGlobalString('MAIN_INFO_ACCOUNTANT_CODE')).'"></td></tr>'."\n";

// Note
print '<tr class="oddeven"><td class="tdtop"><label for="note">'.$langs->trans("Note").'</label></td><td>';
print '<textarea class="flat quatrevingtpercent" name="note" id="note" rows="'.ROWS_2.'">'.(request()->has('note') ? request()->input('note') : getDolGlobalString('MAIN_INFO_ACCOUNTANT_NOTE')).'</textarea></td></tr>';
print '</td></tr>';

print '</table>';

print $form->buttonsSaveCancel("Save", '', array(), false, 'reposition');

print '<br>';


// IT service provider

print load_fiche_titre($langs->trans("ITProvider"), '', 'hdd', 0, '', '', '', '');

print '<table class="noborder centpercent editmode">';
print '<tr class="liste_titre"><th class="titlefieldcreate wordbreak">'.$langs->trans("CompanyInfo").'</th><th></th></tr>'."\n";

// Name of Accountant Company
print '<tr class="oddeven"><td><label for="name">'.$langs->trans("CompanyName").'</label></td><td>';
print '<input name="itprovider_nom" id="itprovider_name" class="minwidth200" value="'.dol_escape_htmltag(request()->has('itprovider_nom') ? request()->input('itprovider_nom') : getDolGlobalString('MAIN_INFO_ITPROVIDER_NAME')).'"'.(!getDolGlobalString('MAIN_INFO_ITPROVIDER_NAME') ? ' autofocus="autofocus"' : '').'></td></tr>'."\n";

// Address
print '<tr class="oddeven"><td><label for="address">'.$langs->trans("CompanyAddress").'</label></td><td>';
print '<textarea name="itprovider_address" id="itprovider_address" class="quatrevingtpercent" rows="'.ROWS_2.'">';
print dolPrintText(request()->has('itprovider_address') ? request()->input('itprovider_address') : getDolGlobalString('MAIN_INFO_ITPROVIDER_ADDRESS'));
print '</textarea></td></tr>'."\n";

// ZIP
print '<tr class="oddeven"><td><label for="zipcode">'.$langs->trans("CompanyZip").'</label></td><td>';
print '<input class="width100" name="itprovider_zipcode" id="itprovider_zipcode" value="'.dol_escape_htmltag(request()->has('itprovider_zipcode') ? request()->input('itprovider_zipcode') : getDolGlobalString('MAIN_INFO_ITPROVIDER_ZIP')).'"></td></tr>'."\n";

// Town/City
print '<tr class="oddeven"><td><label for="itprovider_town">'.$langs->trans("CompanyTown").'</label></td><td>';
print '<input name="itprovider_town" class="minwidth100" id="itprovider_town" value="'.dol_escape_htmltag(request()->has('itprovider_town') ? request()->input('itprovider_town') : getDolGlobalString('MAIN_INFO_ITPROVIDER_TOWN')).'"></td></tr>'."\n";

// Country
print '<tr class="oddeven"><td><label for="selectitprovider_country_id">'.$langs->trans("Country").'</label></td><td class="maxwidthonsmartphone">';
print img_picto('', 'globe-americas', 'class="pictofixedwidth"');
print $form->select_country((request()->has('itprovider_country_id') ? request()->integer('itprovider_country_id', 0) : getDolGlobalString('MAIN_INFO_ITPROVIDER_COUNTRY')), 'itprovider_country_id');
print info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionarySetup"), 1);
print '</td></tr>'."\n";

// State
print '<tr class="oddeven"><td><label for="itprovider_state_id">'.$langs->trans("State").'</label></td><td class="maxwidthonsmartphone">';
print img_picto('', 'state', 'class="pictofixedwidth"');
print $formcompany->select_state((request()->has('itprovider_state_id') ? request()->integer('itprovider_state_id', 0) : getDolGlobalString('MAIN_INFO_ITPROVIDER_STATE')), (request()->has('itprovider_country_id') ? request()->integer('itprovider_country_id', 0) : getDolGlobalString('MAIN_INFO_ITPROVIDER_COUNTRY')), 'itprovider_state_id');
print '</td></tr>'."\n";

// Telephone
print '<tr class="oddeven"><td><label for="itprovider_phone">'.$langs->trans("Phone").'</label></td><td>';
print img_picto('', 'object_phoning', '', 0, 0, 0, '', 'pictofixedwidth');
print '<input name="itprovider_phone" id="itprovider_phone" class="maxwidth150 widthcentpercentminusx" value="'.dol_escape_htmltag(request()->has('itprovider_phone') ? request()->input('itprovider_phone') : getDolGlobalString('MAIN_INFO_ITPROVIDER_PHONE')).'"></td></tr>';
print '</td></tr>'."\n";

// Fax
/*
print '<tr class="oddeven"><td><label for="itprovider_fax">'.$langs->trans("Fax").'</label></td><td>';
print img_picto('', 'object_phoning_fax', '', 0, 0, 0, '', 'pictofixedwidth');
print '<input name="itprovider_fax" id="itprovider_fax" class="maxwidth150 widthcentpercentminusx" value="'.dol_escape_htmltag(request()->has('itprovider_fax') ? request()->input('itprovider_fax') : getDolGlobalString('MAIN_INFO_ITPROVIDER_FAX')).'"></td></tr>';
print '</td></tr>'."\n";
*/

// eMail
print '<tr class="oddeven"><td><label for="itprovider_email">'.$langs->trans("EMail").'</label></td><td>';
print img_picto('', 'object_email', '', 0, 0, 0, '', 'pictofixedwidth');
print '<input name="itprovider_mail" id="itprovider_email" class="maxwidth300 widthcentpercentminusx" value="'.dol_escape_htmltag(request()->has('itprovider_mail') ? request()->input('itprovider_mail') : getDolGlobalString('MAIN_INFO_ITPROVIDER_MAIL')).'"></td></tr>';
print '</td></tr>'."\n";

// Web
print '<tr class="oddeven"><td><label for="itprovider_web">'.$langs->trans("Web").'</label></td><td>';
print img_picto('', 'globe', '', 0, 0, 0, '', 'pictofixedwidth');
print '<input name="itprovider_web" id="itprovider_web" class="maxwidth300 widthcentpercentminusx" value="'.dol_escape_htmltag(request()->has('itprovider_web') ? request()->input('itprovider_web') : getDolGlobalString('MAIN_INFO_ITPROVIDER_WEB')).'"></td></tr>';
print '</td></tr>'."\n";

// Code
print '<tr class="oddeven"><td><label for="itprovider_idprof1">'.$langs->transcountry("ProfId1", $mysoc->country_code).'</label></td><td>';
print '<input name="itprovider_idprof1" id="itprovider_idprof1" class="minwidth100" value="'.dol_escape_htmltag(request()->has('itprovider_idprof1') ? request()->input('itprovider_idprof1') : getDolGlobalString('MAIN_INFO_ITPROVIDER_IDPROF1')).'"></td></tr>'."\n";

// Code
print '<tr class="oddeven"><td><label for="itprovider_code">'.$langs->trans("AccountantFileNumber").'</label></td><td>';
print '<input name="itprovider_code" id="itprovider_code" class="minwidth100" value="'.dol_escape_htmltag(request()->has('itprovider_code') ? request()->input('itprovider_code') : getDolGlobalString('MAIN_INFO_ITPROVIDER_CODE')).'"></td></tr>'."\n";

// Note
print '<tr class="oddeven"><td class="tdtop"><label for="itprovider_note">'.$langs->trans("Note").'</label></td><td>';
print '<textarea class="flat quatrevingtpercent" name="itprovider_note" id="itprovider_note" rows="'.ROWS_2.'">'.(request()->has('itprovider_note') ? request()->input('itprovider_note') : getDolGlobalString('MAIN_INFO_ITPROVIDER_NOTE')).'</textarea></td></tr>';
print '</td></tr>';

print '</table>';

print $form->buttonsSaveCancel("Save", '', array(), false, 'reposition');

print '</form>';


llxFooter();

$db->close();
