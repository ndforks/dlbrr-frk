<?php
/* Copyright (C) 2007      Patrick Raguin       <patrick.raguin@gmail.com>
 * Copyright (C) 2007-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2009-2011 Regis Houssin        <regis.houssin@inodbox.com>
 * Copyright (C) 2016      Meziane Sof          <virtualsof@yahoo.fr>
 * Copyright (C) 2024-2026	MDW						<mdeweerd@users.noreply.github.com>
 * Copyright (C) 2024-2025  Frédéric France         <frederic.france@free.fr>
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
 *		\file       htdocs/admin/menus/edit.php
 *		\ingroup    core
 *		\brief      Tool to edit menus
 */

// Load Dolibarr environment
require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/Core/class/html.formadmin.class.php';
require_once DOL_DOCUMENT_ROOT.'/Core/class/menubase.class.php';

/**
 * @var Conf $conf
 * @var DoliDB $db
 * @var HookManager $hookmanager
 * @var Translate $langs
 * @var User $user
 */

// Load translation files required by the page
$langs->loadLangs(array("other", "admin", "uxdocumentation"));

$cancel = request()->input('cancel'); // We click on a Cancel button
$confirm = request()->input('confirm');

if (!$user->admin) {
	abort(403);
}

$dirstandard = array();
$dirsmartphone = array();
$dirmenus = array_merge(array("/core/menus/"), (array) $conf->modules_parts['menus']);
foreach ($dirmenus as $dirmenu) {
	$dirstandard[] = $dirmenu.'standard';
	$dirsmartphone[] = $dirmenu.'smartphone';
}

$action = request()->input('action');

$menu = new Menubase($db);

$menu_handler_top = getDolGlobalString('MAIN_MENU_STANDARD');
$menu_handler_smartphone = getDolGlobalString('MAIN_MENU_SMARTPHONE');
$menu_handler_top = preg_replace('/_backoffice.php/i', '', $menu_handler_top);
$menu_handler_top = preg_replace('/_frontoffice.php/i', '', $menu_handler_top);
$menu_handler_smartphone = preg_replace('/_backoffice.php/i', '', $menu_handler_smartphone);
$menu_handler_smartphone = preg_replace('/_frontoffice.php/i', '', $menu_handler_smartphone);

$menu_handler = $menu_handler_top;

if (request()->input('handler_origine')) {
	$menu_handler = request()->input('handler_origine');
}
if (request()->input('menu_handler')) {
	$menu_handler = request()->input('menu_handler');
}



/*
 * Actions
 */

if ($action == 'add') {
	if ($cancel) {
		header("Location: ".DOL_URL_ROOT."/admin/menus/index.php?menu_handler=".$menu_handler);
		exit;
	}

	$leftmenu = '';
	$mainmenu = '';
	if (request()->input('menuIdParent') && !is_numeric(request()->input('menuIdParent'))) {
		$tmp = explode('&', request()->input('menuIdParent'));
		foreach ($tmp as $s) {
			if (preg_match('/fk_mainmenu=/', $s)) {
				$mainmenu = preg_replace('/fk_mainmenu=/', '', $s);
			}
			if (preg_match('/fk_leftmenu=/', $s)) {
				$leftmenu = preg_replace('/fk_leftmenu=/', '', $s);
			}
		}
	}

	$langs->load("errors");

	$error = 0;
	if (!request()->input('menu_handler')) {
		setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentities("MenuHandler")), null, 'errors');
		$action = 'create';
		$error++;
	}
	if (!$error && !request()->input('type')) {
		setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentities("Position")), null, 'errors');
		$action = 'create';
		$error++;
	}
	if (!$error && !request()->input('url')) {
		setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("URL")), null, 'errors');
		$action = 'create';
		$error++;
	}
	if (!$error && !request()->input('titre')) {
		setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Title")), null, 'errors');
		$action = 'create';
		$error++;
	}
	if (!$error && request()->input('menuIdParent') && request()->input('type') == 'top') {
		setEventMessages($langs->trans("ErrorTopMenuMustHaveAParentWithId0"), null, 'errors');
		$action = 'create';
		$error++;
	}
	if (!$error && !request()->input('menuIdParent') && request()->input('type') == 'left') {
		setEventMessages($langs->trans("ErrorLeftMenuMustHaveAParentId"), null, 'errors');
		$action = 'create';
		$error++;
	}

	if (!$error) {
		$menu->menu_handler = preg_replace('/_menu$/', '', request()->input('menu_handler'));
		$menu->type = (string) request()->input('type');
		$menu->title = (string) request()->input('titre');
		$menu->prefix = (string) request()->input('picto');
		$menu->url = (string) request()->input('url');
		$menu->langs = (string) request()->input('langs');
		$menu->position = request()->integer('position', 0);
		$menu->enabled = (string) request()->input('enabled');
		$menu->perms = (string) request()->input('perms');
		$menu->target = (string) request()->input('target');
		$menu->showtopmenuinframe = request()->integer('showtopmenuinframe', 0);
		$menu->user = request()->integer('user', 0);
		$menu->mainmenu = (string) request()->input('propertymainmenu');
		if (is_numeric(request()->input('menuIdParent'))) {
			$menu->fk_menu = (int) request()->input('menuIdParent');
		} else {
			if (request()->input('type') == 'top') {
				$menu->fk_menu = 0;
			} else {
				$menu->fk_menu = -1;
			}
			$menu->fk_mainmenu = $mainmenu;
			$menu->fk_leftmenu = $leftmenu;
		}

		$result = $menu->create($user);
		if ($result > 0) {
			header("Location: ".DOL_URL_ROOT."/admin/menus/index.php?menu_handler=".request()->input('menu_handler'));
			exit;
		} else {
			$action = 'create';
			setEventMessages($menu->error, $menu->errors, 'errors');
		}
	}
}

if ($action == 'update') {
	if (!$cancel) {
		$leftmenu = '';
		$mainmenu = '';
		if (request()->input('menuIdParent') && !is_numeric(request()->input('menuIdParent'))) {
			$tmp = explode('&', request()->input('menuIdParent'));
			foreach ($tmp as $s) {
				if (preg_match('/fk_mainmenu=/', $s)) {
					$mainmenu = preg_replace('/fk_mainmenu=/', '', $s);
				}
				if (preg_match('/fk_leftmenu=/', $s)) {
					$leftmenu = preg_replace('/fk_leftmenu=/', '', $s);
				}
			}
		}

		$error = 0;
		if (!request()->input('url')) {
			setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("URL")), null, 'errors');
			$action = 'create';
			$error++;
		}

		if (!$error) {
			$result = $menu->fetch(request()->integer('menuId', 0));
			if ($result > 0) {
				$menu->title = (string) request()->input('titre');
				$menu->prefix = (string) request()->input('picto');
				$menu->leftmenu = (string) request()->input('leftmenu');
				$menu->url = (string) request()->input('url');
				$menu->langs = (string) request()->input('langs');
				$menu->position = request()->integer('position', 0);
				$menu->enabled = (string) request()->input('enabled');
				$menu->perms = (string) request()->input('perms');
				$menu->target = (string) request()->input('target');
				$menu->user = request()->integer('user', 0);
				$menu->mainmenu = (string) request()->input('propertymainmenu');
				$menu->showtopmenuinframe = request()->integer('showtopmenuinframe', 0);

				if (is_numeric(request()->input('menuIdParent'))) {
					$menu->fk_menu = (int) request()->input('menuIdParent');
				} else {
					if (request()->input('type') == 'top') {
						$menu->fk_menu = 0;
					} else {
						$menu->fk_menu = -1;
					}
					$menu->fk_mainmenu = $mainmenu;
					$menu->fk_leftmenu = $leftmenu;
				}

				$result = $menu->update($user);
				if ($result > 0) {
					setEventMessages($langs->trans("RecordModifiedSuccessfully"), null, 'mesgs');
				} else {
					setEventMessages($menu->error, $menu->errors, 'errors');
				}
			} else {
				setEventMessages($menu->error, $menu->errors, 'errors');
			}

			$action = "edit";

			header("Location: ".DOL_URL_ROOT."/admin/menus/index.php?menu_handler=".$menu_handler);
			exit;
		} else {
			$action = 'edit';
		}
	} else {
		header("Location: ".DOL_URL_ROOT."/admin/menus/index.php?menu_handler=".$menu_handler);
		exit;
	}
}



/*
 * View
 */

$form = new Form($db);
$formadmin = new FormAdmin($db);

llxHeader('', $langs->trans('Menu'), '', '', 0, 0, '', '', '', 'mod-admin page-menus_edit');


if ($action == 'create') {
	print '<script type="text/javascript">
    jQuery(document).ready(function() {
    	function init_topleft()
    	{
    		if (jQuery("#topleft").val() == \'top\')
    		{
				jQuery("#menuIdParent").prop("disabled", true);
	    		jQuery("#menuIdParent").val(\'\');
				jQuery("#propertymainmenu").removeAttr("disabled");
	    		jQuery("#propertymainmenu").val(\'\');
			}
    		if (jQuery("#topleft").val() == \'left\')
    		{
				jQuery("#menuIdParent").removeAttr("disabled");
				jQuery("#propertymainmenu").prop("disabled", true);
    		}
    	}
    	init_topleft();
    	jQuery("#topleft").click(function() {
    		init_topleft();
    	});
    });
    </script>';

	print load_fiche_titre($langs->trans("NewMenu"), '', 'title_setup');

	print '<form action="'.DOL_URL_ROOT.'/admin/menus/edit.php?menuId='.request()->integer('menuId', 0).'" method="POST" name="formmenucreate">';
	print '<input type="hidden" name="action" value="add">';
	print '<input type="hidden" name="token" value="'.newToken().'">';

	print dol_get_fiche_head();

	print '<div class="div-table-responsive">'; // You can use div-table-responsive-no-min if you don't need reserved height for your table
	print '<table class="border centpercent">';

	// Id
	$parent_rowid = request()->integer('menuId', 0);
	$parent_mainmenu = '';
	$parent_leftmenu = '';
	$parent_langs = '';
	$parent_level = '';

	if (request()->integer('menuId', 0)) {
		$sql = "SELECT m.rowid, m.mainmenu, m.leftmenu, m.level, m.langs";
		$sql .= " FROM ".MAIN_DB_PREFIX."menu as m";
		$sql .= " WHERE m.rowid = ".(request()->integer('menuId', 0));
		$res = $db->query($sql);
		if ($res) {
			while ($menu = $db->fetch_array($res)) {
				$parent_rowid = $menu['rowid'];
				$parent_mainmenu = $menu['mainmenu'];
				$parent_leftmenu = $menu['leftmenu'];
				$parent_langs = $menu['langs'];
				$parent_level = $menu['level'];
			}
		}
	}

	// Handler
	print '<tr><td class="fieldrequired">'.$form->textwithpicto($langs->trans('MenuHandler'), $langs->trans('DetailMenuHandler')).'</td>';
	print '<td>';
	$formadmin->select_menu_families($menu_handler.(preg_match('/_menu/', $menu_handler) ? '' : '_menu'), 'menu_handler', array_merge($dirstandard, $dirsmartphone));
	print '</td>';
	print '<td></td></tr>';

	// User
	print '<tr><td class="nowrap fieldrequired">'.$form->textwithpicto($langs->trans('MenuForUsers'), $langs->trans('DetailUser')).'</td>';
	print '<td><select class="flat width150" name="user" id="menuuser">';
	print '<option value="2" selected>'.$langs->trans("AllMenus").'</option>';
	print '<option value="0">'.$langs->trans('Internal').'</option>';
	print '<option value="1">'.$langs->trans('External').'</option>';
	print '</select>';
	print ajax_combobox('menuuser');
	print '</td>';
	print '<td></td></tr>';

	// Type
	print '<tr><td class="fieldrequired">'.$langs->trans('Position').'</td><td>';
	if ($parent_rowid) {
		print $langs->trans('Left');
		print '<input type="hidden" name="type" value="left">';
	} else {
		print '<select name="type" class="flat width150" id="topleft">';
		print '<option value="">&nbsp;</option>';
		print '<option value="top"'.(request()->input('type') == 'top' ? ' selected' : '').'>'.$langs->trans('Top').'</option>';
		print '<option value="left"'.(request()->input('type') == 'left' ? ' selected' : '').'>'.$langs->trans('Left').'</option>';
		print '</select>';
		print ajax_combobox('topleft');

		print '<script>
		jQuery(document).ready(function() {
			jQuery("#topleft").on("change", function() {
				console.log("We change the type topleft with ");
				if (jQuery("#topleft").val() == "top") {
					jQuery(".menuidparent").hide();
				} else {
					jQuery(".menuidparent").show();
				}
			});
		});
		</script>';
	}
	print '</td><td>'.$langs->trans('DetailType').'</td></tr>';

	// Mainmenu code
	print '<tr><td class="fieldrequired">'.$langs->trans('MainMenuCode').'</td>';
	print '<td><input type="text" class="minwidth300" id="propertymainmenu" name="propertymainmenu" value="'.(request()->has('propertymainmenu') ? request()->input('propertymainmenu') : '').'"></td>';
	print '<td>';
	print $langs->trans("Example").': mytopmenukey';
	print '</td></tr>';

	// MenuId Parent
	print '<tr class="menuidparent"><td>'.$langs->trans('MenuIdParent').'</td>';
	if ($parent_rowid) {
		print '<td>'.$parent_rowid.'<input type="hidden" name="menuIdParent" value="'.$parent_rowid.'"></td>';
	} else {
		print '<td><input type="text" class="minwidth300" id="menuIdParent" name="menuIdParent" value="'.(request()->has('menuIdParent') ? request()->input('menuIdParent') : '').'"></td>';
	}
	print '<td>'.$langs->trans('DetailMenuIdParent');
	print ', '.$langs->trans("Example").': fk_mainmenu=abc&fk_leftmenu=def';
	print '</td></tr>';

	// Title
	print '<tr><td class="fieldrequired">'.$langs->trans('Title').'</td>';
	print '<td><input type="text" class="minwidth300" name="titre" value="'.dol_escape_htmltag(request()->input('titre')).'"></td><td>'.$langs->trans('DetailTitre').'</td></tr>';

	// Langs
	print '<tr><td>'.$langs->trans('LangFile').'</td>';
	print '<td><input type="text" class="minwidth300" name="langs" value="'.dol_escape_htmltag($parent_langs).'"></td><td>'.$langs->trans('DetailLangs').'</td></tr>';

	// Picto
	print '<tr><td>'.$langs->trans('Image').'</td>';
	print '<td><input type="text" class="minwidth300" name="picto" value="'.dol_escape_htmltag(request()->input('picto')).'"></td><td>'.$langs->trans('Example').': fa-globe-americas';
	print '<span class="opacitymedium small">';
	print ' &nbsp; &nbsp; ';
	print dolButtonToOpenUrlInDialogPopup('popup_picto_id', $langs->transnoentitiesnoconv("DocIconsList"), $langs->transnoentitiesnoconv("DocIconsList"), '/admin/tools/ui/components/icons.php?hidenavmenu=1&displayMode=icon-only#img-picto-section-list', '', '');
	print '</span>';
	print '</td></tr>';

	// URL
	print '<tr><td class="fieldrequired">'.$langs->trans('URL').'</td>';
	print '<td><input type="text" class="minwidth500" name="url" value="'.dol_escape_htmltag(request()->input('url')).'"></td><td>'.$langs->trans('DetailUrl').'</td></tr>';

	// Target
	print '<tr><td>'.$langs->trans('OpenLinkInto').'</td><td><select class="flat" name="target" id="target">';
	print '<option value=""'.(isset($menu->target) && $menu->target == "" ? ' selected' : '').'>'.$langs->trans('SameWindow').'</option>';
	print '<option value="_blank"'.(isset($menu->target) && $menu->target == "_blank" ? ' selected' : '').'>'.$langs->trans('NewWindow').'</option>';
	print '</select>';
	print ajax_combobox("target");
	print '</td><td></td></tr>';

	// Show URL into a frame
	if (getDolGlobalString("MAIN_FEATURE_TO_SHOW_TOP_MENU_URL_IN_FRAME")) {
		print '<tr class="hideforleftmenu"><td>'.$langs->trans('ShowTopMenuURLIntoAFrame').'</td>';
		print '<td><input type="checkbox" value="1" name="showtopmenuinframe"'.(request()->integer('showtopmenuinframe', 0) ? ' checked="checked"' : '').'"></td><td></td></tr>';
	}

	// Position
	print '<tr><td>'.$langs->trans('Position').'</td>';
	print '<td><input type="number" class="minwidth50 maxwidth75" name="position" value="'.(request()->has('position') ? request()->integer('position', 0) : 100).'"></td><td>'.$langs->trans('DetailPosition').'</td></tr>';

	// Enabled
	print '<tr><td>'.$langs->trans('Enabled').'</td>';
	print '<td><input type="text" class="minwidth500" name="enabled" value="'.(request()->has('enabled') ? request()->input('enabled') : '1').'"></td><td>'.$langs->trans('DetailEnabled').'</td></tr>';

	// Perms
	print '<tr><td>'.$langs->trans('Rights').'</td>';
	print '<td><input type="text" class="minwidth500" name="perms" value="'.(request()->has('perms') ? request()->input('perms') : '1').'"></td><td>'.$langs->trans('DetailRight').'</td></tr>';

	print '</table>';
	print '</div>';

	print dol_get_fiche_end();

	print $form->buttonsSaveCancel();

	print '</form>';
} elseif ($action == 'edit') {
	print load_fiche_titre($langs->trans("ModifMenu"), '', 'title_setup');

	print '<form action="./edit.php" method="POST" name="formmenuedit">';
	print '<input type="hidden" name="action" value="update">';
	print '<input type="hidden" name="token" value="'.newToken().'">';
	print '<input type="hidden" name="handler_origine" value="'.$menu_handler.'">';
	print '<input type="hidden" name="menuId" value="'.request()->integer('menuId', 0).'">';

	print dol_get_fiche_head();

	print '<div class="div-table-responsive">'; // You can use div-table-responsive-no-min if you don't need reserved height for your table
	print '<table class="border centpercent">';

	$menu = new Menubase($db);
	$result = $menu->fetch(request()->integer('menuId', 0));
	//var_dump($menu);

	// Id
	print '<tr><td>'.$form->textwithpicto($langs->trans('MenuID'), $langs->trans('DetailId')).'</td><td>'.$menu->id.'</td><td></td></tr>';

	// Module
	print '<tr><td>'.$form->textwithpicto($langs->trans('MenuModule'), $langs->trans('DetailMenuModule')).'</td><td>'.(empty($menu->module) ? 'Core' : $menu->module).'</td><td></td></tr>';

	// Handler
	if ($menu->menu_handler == 'all') {
		$handler = $langs->trans('AllMenus');
	} else {
		$handler = $menu->menu_handler;
	}
	print '<tr><td class="fieldrequired">'.$form->textwithpicto($langs->trans('MenuHandler'), $langs->transnoentitiesnoconv("DetailMenuHandler")).'</td><td>'.$handler.'</td>';
	print '<td></td></tr>';

	// User
	print '<tr><td class="nowrap fieldrequired">'.$langs->trans('MenuForUsers').'</td><td>';
	print '<select class="flat" name="user" id="menuuser">';
	print '<option value="2"'.($menu->user == 2 ? ' selected' : '').'>'.$langs->trans("AllMenus").'</option>';
	print '<option value="0"'.($menu->user == 0 ? ' selected' : '').'>'.$langs->trans('Internal').'</option>';
	print '<option value="1"'.($menu->user == 1 ? ' selected' : '').'>'.$langs->trans('External').'</option>';
	print '</select>';
	print ajax_combobox('menuuser');
	print '</td><td>'.$langs->trans('DetailUser').'</td></tr>';

	// Type
	print '<tr><td class="fieldrequired">'.$langs->trans('Position').'</td>';
	print '<td>'.$langs->trans(ucfirst($menu->type));
	print '<input type="hidden" name="type" value="'.$menu->type.'">';
	print '</td><td>';
	print $langs->transnoentitiesnoconv("DetailType");
	print '</td></tr>';

	// Mainmenu code
	if ($menu->type == 'top') {
		print '<tr><td class="fieldrequired">'.$langs->trans('MainMenuCode').'</td>';
		print '<td><input type="text" class="minwidth300" id="propertymainmenu" name="propertymainmenu" value="'.(request()->input('propertymainmenu') ? request()->input('propertymainmenu') : $menu->mainmenu).'"></td>';
		print '<td>';
		print $langs->trans("Example").': mytopmenukey';
		print '</td></tr>';
	}

	// MenuId Parent
	print '<tr class="hideforleftmenu"><td class="fieldrequired">'.$langs->trans('MenuIdParent');
	print '</td>';
	$valtouse = $menu->fk_menu;
	if ($menu->fk_mainmenu) {
		$valtouse = 'fk_mainmenu='.$menu->fk_mainmenu;
	}
	if ($menu->fk_leftmenu) {
		$valtouse .= '&fk_leftmenu='.$menu->fk_leftmenu;
	}
	print '<td><input type="text" name="menuIdParent" value="'.dol_escape_htmltag(request()->has('menuIdParent') ? request()->input('menuIdParent') : $valtouse).'" class="minwidth300"></td>';
	print '<td>'.$langs->trans('DetailMenuIdParent');
	print ', <span class="opacitymedium">'.$langs->trans("Example").': fk_mainmenu=abc&fk_leftmenu=def</span>';
	print '</td></tr>';

	// Level
	//print '<tr><td>'.$langs->trans('Level').'</td><td>'.$menu->level.'</td><td>'.$langs->trans('DetailLevel').'</td></tr>';

	// Title
	print '<tr><td class="fieldrequired">'.$langs->trans('Title').'</td>';
	print '<td><input type="text" class="minwidth300" name="titre" value="'.dol_escape_htmltag($menu->title).'"></td><td>'.$langs->trans('DetailTitre').'</td></tr>';

	// Langs
	print '<tr><td>'.$langs->trans('LangFile').'</td>';
	print '<td><input type="text" class="minwidth300" name="langs" value="'.dol_escape_htmltag($menu->langs).'"></td><td>'.$langs->trans('DetailLangs').'</td></tr>';

	// Picto
	print '<tr><td>'.$langs->trans('Image').'</td>';
	print '<td><input type="text" class="minwidth300" name="picto" value="'.dol_escape_htmltag($menu->prefix).'"></td><td>'.$langs->trans('Example').': fa-globe-americas';
	print '<span class="opacitymedium small">';
	print ' &nbsp; &nbsp; ';
	print dolButtonToOpenUrlInDialogPopup('popup_picto_id', $langs->transnoentitiesnoconv("DocIconsList"), $langs->transnoentitiesnoconv("DocIconsList"), '/admin/tools/ui/components/icons.php?hidenavmenu=1&displayMode=icon-only#img-picto-section-list', '', '');
	print '</span>';
	print '</td></tr>';

	// URL
	print '<tr><td class="fieldrequired">'.$langs->trans('URL').'</td>';
	print '<td><input type="text" class="quatrevingtpercent" name="url" value="'.dol_escape_htmltag($menu->url).'"></td><td>'.$langs->trans('DetailUrl').'</td></tr>';

	// Target
	print '<tr><td>'.$langs->trans('OpenLinkInto').'</td><td>';
	print '<select class="flat" id="target" name="target">';
	print '<option value=""'.($menu->target == "" ? ' selected' : '').'>'.$langs->trans('SameWindow').'</option>';
	print '<option value="_blank"'.($menu->target == "_blank" ? ' selected' : '').'>'.$langs->trans('NewWindow').'</option>';
	print '</select>';
	print ajax_combobox("target");
	print '</td><td></td></tr>';

	// Show URL into a frame
	if (getDolGlobalString("MAIN_FEATURE_TO_SHOW_TOP_MENU_URL_IN_FRAME") && $menu->type == 'top') {
		print '<tr class="hideforleftmenu"><td>'.$langs->trans('ShowTopMenuURLIntoAFrame').'</td>';
		print '<td><input type="checkbox" value="1" name="showtopmenuinframe" '.($menu->showtopmenuinframe ? ' checked="checked"' : '').'"></td><td></td></tr>';
	}

	// Position
	print '<tr><td>'.$langs->trans('Position').'</td>';
	print '<td><input type="number" class="minwidth50 maxwidth75" name="position" value="'.((int) $menu->position).'"></td><td>'.$langs->trans('DetailPosition').'</td></tr>';

	// Enabled
	print '<tr><td>'.$langs->trans('Enabled').'</td>';
	print '<td><input type="text" class="minwidth500" name="enabled" value="'.dol_escape_htmltag($menu->enabled).'"></td><td>'.$langs->trans('DetailEnabled');
	if (!empty($menu->enabled)) {
		print ' <span class="opacitymedium">('.$langs->trans("ConditionIsCurrently").':</span> '.yn((int) dol_eval((string) $menu->enabled, 1, 1, '1') <= 0 ? 0 : 1).')';
	}
	print '</td></tr>';

	// Perms
	print '<tr><td>'.$langs->trans('Rights').'</td>';
	print '<td><input type="text" class="minwidth500" name="perms" value="'.dol_escape_htmltag($menu->perms).'"></td><td>'.$langs->trans('DetailRight');
	if (!empty($menu->perms)) {
		print ' <span class="opacitymedium">('.$langs->trans("ConditionIsCurrently").':</span> '.yn((int) dol_eval((string) $menu->perms, 1, 1, '1') <= 0 ? 0 : 1).')';
	}
	print '</td></tr>';

	print '</table>';
	print '</div>';

	print dol_get_fiche_end();

	print $form->buttonsSaveCancel();

	print '</form>';

	print '<br>';
}

// End of page
llxFooter();
$db->close();
