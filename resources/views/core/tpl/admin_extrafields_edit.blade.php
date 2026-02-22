{{-- Blade template version --}}
<?php
/* Copyright (C) 2010-2012	Laurent Destailleur	<eldy@users.sourceforge.net>
 * Copyright (C) 2012		Regis Houssin		<regis.houssin@inodbox.com>
 * Copyright (C) 2018-2025  Frédéric France     <frederic.france@free.fr>
 * Copyright (C) 2025		MDW					<mdeweerd@users.noreply.github.com>
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
 * The following vars must be defined:
 * $type2label
 * $form
 * $conf, $lang,
 * The following vars may also be defined:
 * $elementtype
 */

/**
 * @var Conf $conf
 * @var DoliDB $db
 * @var ExtraFields $extrafields
 * @var Form $form
 * @var Translate $langs
 *
 * @var string $attrname
 * @var string $action
 * @var string $elementtype
 * @var string $textobject
 * @var string[] $type2label
 */
// Protection to avoid direct call of template
if (empty($conf) || !is_object($conf)) {
	print "Error, template page can't be called as URL";
	exit(1);
}


$langs->load("modulebuilder");

$listofexamplesforlink = 'Societe:societe/class/societe.class.php<br>Contact:contact/class/contact.class.php<br>Product:product/class/product.class.php<br>Project:projet/class/project.class.php<br>...';

?>

<!-- BEGIN PHP TEMPLATE admin_extrafields_edit.tpl.php -->
<script>
	jQuery(document).ready(function() {
		function init_typeoffields(type)
		{
			console.log("admin_extrafields_edit select a new type (edit) = "+type);
			var size = jQuery("#size");
			var computed_value = jQuery("#computed_value");
			var langfile = jQuery("#langfile");
			var default_value = jQuery("#default_value");
			var unique = jQuery("#unique");
			var required = jQuery("#required");
			var alwayseditable = jQuery("#alwayseditable");
			var emptyonclone = jQuery("#emptyonclone");
			var list = jQuery("#list");
			var totalizable = jQuery("#totalizable");
			<?php
			if ((GETPOST('type', 'alpha') != "select") && (GETPOST('type', 'alpha') != "sellist")) {
				print 'jQuery("#value_choice").hide();';
			}

			if (in_array(GETPOST('type', 'alpha'), ["separate", 'point', 'linestrg', 'polygon'])) {
				print "jQuery('#size, #default_value, #langfile').val('').prop('disabled', true);";
				print 'jQuery("#value_choice").hide();';
			}
			?>

			// Case of computed field
			if (type == 'varchar' || type == 'int' || type == 'double' || type == 'price') {
				jQuery("tr.extra_computed_value").show();
			} else {
				computed_value.val(''); jQuery("tr.extra_computed_value").hide();
			}
			if (computed_value.val())
			{
				console.log("We enter a computed formula");
				jQuery("#default_value").val('');
				/* jQuery("#unique, #required, #alwayseditable, #list").removeAttr('checked'); */
				jQuery("#default_value, #unique, #required, #alwayseditable, #emptyonclone, #list").attr('disabled', true);
				jQuery("tr.extra_default_value, tr.extra_unique, tr.extra_required, tr.extra_alwayseditable, tr.extra_emptyonclone, tr.extra_list").hide();
			}
			else
			{
				console.log("No computed formula");
				jQuery("#default_value, #unique, #required, #alwayseditable, #emptyonclone, #list").attr('disabled', false);
				jQuery("tr.extra_default_value, tr.extra_unique, tr.extra_required, tr.extra_alwayseditable, tr.extra_emptyonclone, tr.extra_list").show();
			}

			// Case of ai prompt
			if (type == 'text' || type == 'varchar' || type == 'int' || type == 'double' || type == 'price' || type == 'html') {
				jQuery("tr.extra_ai_prompt").show();
			} else {
				jQuery(ai_prompt).val(''); jQuery("tr.extra_ai_prompt").hide();
			}

			if (type == 'date') { size.val('').prop('disabled', true); unique.removeAttr('disabled'); jQuery("#value_choice").hide();jQuery("#helpchkbxlst").hide(); }
			else if (type == 'datetime') { size.val('').prop('disabled', true); unique.removeAttr('disabled'); jQuery("#value_choice").hide(); jQuery("#helpchkbxlst").hide();}
			else if (type == 'double')   { size.removeAttr('disabled'); unique.removeAttr('disabled'); jQuery("#value_choice").hide(); jQuery("#helpchkbxlst").hide();}
			else if (type == 'int')      { size.removeAttr('disabled'); unique.removeAttr('disabled'); jQuery("#value_choice").hide(); jQuery("#helpchkbxlst").hide();}
			else if (type == 'text')     { size.removeAttr('disabled'); unique.prop('disabled', true).removeAttr('checked'); jQuery("#value_choice").hide();jQuery("#helpchkbxlst").hide(); }
			else if (type == 'html')     { size.removeAttr('disabled'); unique.prop('disabled', true).removeAttr('checked'); jQuery("#value_choice").hide();jQuery("#helpchkbxlst").hide(); }
			else if (type == 'varchar')  { size.removeAttr('disabled'); unique.removeAttr('disabled'); jQuery("#value_choice").hide();jQuery("#helpchkbxlst").hide(); }
			else if (type == 'password') { size.val('').prop('disabled', true); unique.removeAttr('checked').prop('disabled', true); required.val('').prop('disabled', true); default_value.val('').prop('disabled', true); jQuery("#value_choice").show(); jQuery(".spanforparamtooltip").hide(); jQuery("#helppassword").show();}
			else if (type == 'boolean')  { size.val('').prop('disabled', true); unique.removeAttr('checked').prop('disabled', true); jQuery("#value_choice").hide(); jQuery("#helpchkbxlst").hide();}
			else if (type == 'price')    { size.val('').prop('disabled', true); unique.removeAttr('checked').prop('disabled', true); jQuery("#value_choice").hide(); jQuery("#helpchkbxlst").hide();}
			else if (type == 'pricecy')  { size.val('').prop('disabled', true); unique.removeAttr('checked').prop('disabled', true); jQuery("#value_choice").hide(); jQuery("#helpchkbxlst").hide();}
			else if (type == 'select')   { size.val('').prop('disabled', true); unique.removeAttr('checked').prop('disabled', true); jQuery("#value_choice").show(); jQuery(".spanforparamtooltip").hide(); jQuery("#helpselect").show();}
			else if (type == 'sellist')  { size.val('').prop('disabled', true); unique.removeAttr('checked').prop('disabled', true); jQuery("#value_choice").show(); jQuery(".spanforparamtooltip").hide(); jQuery("#helpsellist").show();}
			else if (type == 'radio')    { size.val('').prop('disabled', true); unique.removeAttr('checked').prop('disabled', true); jQuery("#value_choice").show(); jQuery(".spanforparamtooltip").hide(); jQuery("#helpselect").show();}
			else if (type == 'checkbox') { size.val('').prop('disabled', true); unique.removeAttr('checked').prop('disabled', true); jQuery("#value_choice").show(); jQuery(".spanforparamtooltip").hide(); jQuery("#helpselect").show();}
			else if (type == 'chkbxlst') { size.val('').prop('disabled', true); unique.removeAttr('checked').prop('disabled', true); jQuery("#value_choice").show(); jQuery(".spanforparamtooltip").hide(); jQuery("#helpchkbxlst").show();}
			else if (type == 'link')     { size.val('').prop('disabled', true); unique.removeAttr('disabled'); jQuery("#value_choice").show(); jQuery(".spanforparamtooltip").hide(); jQuery("#helplink").show();}
			else if (type == 'stars')      { size.removeAttr('disabled'); unique.removeAttr('disabled'); jQuery("#value_choice").hide(); jQuery("#helpchkbxlst").hide();}
			else if (type == 'separate') {
				size.val('').prop('disabled', true); unique.removeAttr('checked').prop('disabled', true); required.val('').prop('disabled', true); default_value.val('').prop('disabled', true);
				jQuery("#value_choice").show();
				jQuery(".spanforparamtooltip").hide(); jQuery("#helpseparate").show();
			}
			else {	// type = string
				size.val('').prop('disabled', true);
				unique.removeAttr('disabled');
			}

			if (type == 'separate' || type == 'point' || type == 'linestrg' || type == 'polygon')
			{
				required.removeAttr('checked').prop('disabled', true); alwayseditable.removeAttr('checked').prop('disabled', true); emptyonclone.removeAttr('checked').prop('disabled', true); list.removeAttr('checked').prop('disabled', true);
				jQuery('#size, #default_value, #langfile').val('').prop('disabled', true);
				jQuery('#list').val(3);	// visible on create/update/view form only
			}
			else
			{
				default_value.removeAttr('disabled');
				required.removeAttr('disabled'); alwayseditable.removeAttr('disabled'); emptyonclone.removeAttr('disabled'); list.removeAttr('disabled');
			}
		}
		init_typeoffields(jQuery("#type").val());
		jQuery("#type").change(function() {
			init_typeoffields($(this).val());
		});

		// If we enter a formula, we disable other fields
		jQuery("#computed_value").keyup(function() {
			init_typeoffields(jQuery('#type').val());
		});
	});
</script>

<!-- Form to edit an extra field -->
<form action="{{ $_SERVER["PHP_SELF"] }}?attrname={{ $attrname }}" id="formeditextrafield" method="post">
<input type="hidden" name="token" value="{{ newToken() }}">
<input type="hidden" name="attrname" value="{{ $attrname }}">
<input type="hidden" name="action" value="update">
<input type="hidden" name="rowid" value="{{ empty($rowid) ? '' : $rowid }}">
<input type="hidden" name="enabled" value="{{ dol_escape_htmltag((string) $extrafields->attributes[$elementtype]['enabled'][$attrname]) }}">

{!! dol_get_fiche_head() !!}

<table summary="listofattributes" class="border centpercent">

<?php
$label = $extrafields->attributes[$elementtype]['label'][$attrname];
$type = $extrafields->attributes[$elementtype]['type'][$attrname];
$size = $extrafields->attributes[$elementtype]['size'][$attrname];
$computed = $extrafields->attributes[$elementtype]['computed'][$attrname];
$aiprompt = $extrafields->attributes[$elementtype]['aiprompt'][$attrname];
$default = $extrafields->attributes[$elementtype]['default'][$attrname];
$unique = $extrafields->attributes[$elementtype]['unique'][$attrname];
$required = $extrafields->attributes[$elementtype]['required'][$attrname];
$pos = $extrafields->attributes[$elementtype]['pos'][$attrname];
$alwayseditable = $extrafields->attributes[$elementtype]['alwayseditable'][$attrname];
$emptyonclone = $extrafields->attributes[$elementtype]['emptyonclone'][$attrname];
$param = $extrafields->attributes[$elementtype]['param'][$attrname];
$perms = $extrafields->attributes[$elementtype]['perms'][$attrname];
$langfile = $extrafields->attributes[$elementtype]['langfile'][$attrname];
$list = $extrafields->attributes[$elementtype]['list'][$attrname];
$totalizable = $extrafields->attributes[$elementtype]['totalizable'][$attrname];
$help = $extrafields->attributes[$elementtype]['help'][$attrname];
$entitycurrentorall = $extrafields->attributes[$elementtype]['entityid'][$attrname];
$printable = $extrafields->attributes[$elementtype]['printable'][$attrname];
$enabled = $extrafields->attributes[$elementtype]['enabled'][$attrname];
$css = $extrafields->attributes[$elementtype]['css'][$attrname];
$cssview = $extrafields->attributes[$elementtype]['cssview'][$attrname];
$csslist = $extrafields->attributes[$elementtype]['csslist'][$attrname];

$param_chain = '';
if (is_array($param)) {
	if (($type == 'select') || ($type == 'checkbox') || ($type == 'radio')) {
		foreach ($param['options'] as $key => $value) {
			if (strlen($key)) {
				$param_chain .= $key.','.$value."\n";
			}
		}
	} elseif (($type == 'sellist') || ($type == 'chkbxlst') || ($type == 'link') || ($type == 'password') || ($type == 'separate')) {
		$paramlist = array_keys($param['options']);
		$param_chain = $paramlist[0];
	}
}
?>
<!-- Label -->
<tr><td class="titlefieldcreate fieldrequired">{{ $langs->trans("LabelOrTranslationKey") }}</td><td class="valeur"><input type="text" name="label" size="40" value="{{ $label }}"></td></tr>

<!-- Code -->
<tr><td class="fieldrequired">{{ $form->textwithpicto($langs->trans("AttributeCode"), $langs->trans("AttributeCodeHelp")) }}</td><td class="valeur">{{ $attrname }}</td></tr>

<!-- Type -->
<tr><td class="fieldrequired">{{ $langs->trans("Type") }}</td><td class="valeur">
<?php
// Define list of possible type transition
$typewecanchangeinto = array(
	'varchar' => array('varchar', 'phone', 'mail', 'url', 'ip', 'select', 'password', 'text', 'html'),
	'double' => array('double', 'price'),
	'price' => array('double', 'price'),
	'text' => array('text', 'html'),
	'html' => array('text', 'html'),
	'password' => array('password', 'varchar'),
	'mail' => array('varchar', 'phone', 'mail', 'url', 'ip', 'select'),
	'url' => array('varchar', 'phone', 'mail', 'url', 'ip', 'select'),
	'phone' => array('varchar', 'phone', 'mail', 'url', 'ip', 'select'),
	'ip' => array('varchar', 'phone', 'mail', 'url', 'ip', 'select'),
	'select' => array('varchar', 'phone', 'mail', 'url', 'ip', 'select'),
	'date' => array('date', 'datetime')
);
/* Disabled because text is text on several lines, when varchar is text on 1 line, we should not be able to convert
if ($size <= 255 && in_array($type, array('text', 'html'))) {
	$typewecanchangeinto['text'][] = 'varchar';
}*/

if (in_array($type, array_keys($typewecanchangeinto))) {
	// Combo with list of fields
	if (empty($formadmin)) {
		include_once DOL_DOCUMENT_ROOT.'/core/class/html.formadmin.class.php';
		$formadmin = new FormAdmin($db);
	}
	print $formadmin->selectTypeOfFields('type', GETPOST('type', 'alpha') ? GETPOST('type', 'alpha') : $type, $typewecanchangeinto);
} else {
	print getPictoForType($type);
	print $type2label[$type];
	print '<input type="hidden" name="type" id="type" value="'.$type.'">';
}
?>
</td></tr>

<!-- Size -->
<tr class="extra_size"><td>{{ $langs->trans("Size") }}</td><td><input id="size" type="text" name="size" class="width50" value="{{ $size }}"></td></tr>

<!--  Value (for some fields like password, select list, radio, ...) -->
<tr id="value_choice">
<td>
	{{ $langs->trans("Value") }}
</td>
<td>
	<table class="nobordernopadding">
	<tr><td>
		<textarea name="param" id="param" cols="80" rows="{{ ROWS_4 ?>" spellcheck="false">{{ dol_htmlcleanlastbr($param_chain) }}</textarea>
	</td><td>
	<span id="helpselect" class="spanforparamtooltip">{!! $form->textwithpicto('', $langs->trans("ExtrafieldParamHelpselect"), 1, 'info', '', 0, 2, 'helpvalue1') }}</span>
	<span id="helpsellist" class="spanforparamtooltip">{!! $form->textwithpicto('', $langs->trans("ExtrafieldParamHelpsellist").'<br>'.$langs->trans("ExtrafieldParamHelpsellistb").'<br>'.$langs->trans("ExtrafieldParamHelpsellistc").'<br>'.$langs->trans("ExtrafieldParamHelpsellistd").(getDolGlobalInt('MAIN_FEATUREES_LEVEL') > 0 ? '<br>'.$langs->trans("ExtrafieldParamHelpsellist2") : ''), 1, 'info', '', 0, 2, 'helpvalue2') !!}</span>
	<span id="helpchkbxlst" class="spanforparamtooltip">{!! $form->textwithpicto('', $langs->trans("ExtrafieldParamHelpsellist").'<br>'.$langs->trans("ExtrafieldParamHelpsellistb").'<br>'.$langs->trans("ExtrafieldParamHelpsellistc").'<br>'.$langs->trans("ExtrafieldParamHelpsellistd").(getDolGlobalInt('MAIN_FEATUREES_LEVEL') > 0 ? '<br>'.$langs->trans("ExtrafieldParamHelpsellist2") : ''), 1, 'info', '', 0, 2, 'helpvalue3') !!}</span>
	<span id="helplink" class="spanforparamtooltip">{!! $form->textwithpicto('', $langs->trans("ExtrafieldParamHelplink").'<br><br>'.$langs->trans("Examples").':<br>'.$listofexamplesforlink, 1, 'info', '', 0, 2, 'helpvalue4') !!}</span>
	<span id="helppassword" class="spanforparamtooltip">{!! $form->textwithpicto('', $langs->trans("ExtrafieldParamHelpPassword"), 1, 'info', '', 0, 2, 'helpvalue5') !!}</span>
	<span id="helpseparate" class="spanforparamtooltip">{!! $form->textwithpicto('', $langs->trans("ExtrafieldParamHelpSeparator"), 1, 'info', '', 0, 2, 'helpvalue6') !!}</span>
	</td></tr>
	</table>
</td>
</tr>

<!-- Position -->
<tr><td class="titlefield">{{ $langs->trans("Position") }}</td><td class="valeur"><input type="text" name="pos" class="width50" value="{{ dol_escape_htmltag((string) $pos) }}"></td></tr>

<!-- Language file -->
<tr><td class="titlefield">{{ $langs->trans("LanguageFile") }}</td><td class="valeur"><input type="text" name="langfile" class="minwidth200" value="{{ dol_escape_htmltag($langfile) }}"></td></tr>

<!-- Computed value -->
<tr class="extra_computed_value">
<?php if (!getDolGlobalString('MAIN_STORE_COMPUTED_EXTRAFIELDS')) { ?>
	<td>{{ $form->textwithpicto($langs->trans("ComputedFormula"), $langs->trans("ComputedFormulaDesc", '$db, $langs, $mysoc, $user, $objectoffield').'<br>'.$langs->trans("ComputedFormulaDesc2").'<br><br>'.$langs->trans("ComputedFormulaDesc3"), 1, 'help', '', 0, 2, 'tooltipcompute') }}</td>
<?php } else { ?>
	<td>{{ $form->textwithpicto($langs->trans("ComputedFormula"), $langs->trans("ComputedFormulaDesc", '$db, $langs, $mysoc, $user, $objectoffield').'<br>'.$langs->trans("ComputedFormulaDesc2").'<br><br>'.$langs->trans("ComputedFormulaDesc3")).$form->textwithpicto($langs->trans("Computedpersistent"), $langs->trans("ComputedpersistentDesc"), 1, 'warning') }}</td>
<?php } ?>
<td class="valeur"><textarea name="computed_value" id="computed_value" class="quatrevingtpercent" rows="{{ ROWS_4 ?>">{{ dol_htmlcleanlastbr($computed) }}</textarea></td>
</tr>

<!-- AI Prompt -->
<tr class="extra_ai_prompt">
	<td><?php
	if ($elementtype == "projet") {
		$elementtype = "project";
	}
	$elementprop = getElementProperties($elementtype);
	$object = fetchObjectByElement(0, $elementtype);
	if ($elementprop["module"] == "adherent") {
		$elementprop["module"] = "member";
	}
	if ($elementprop["module"] == "projet") {
		$elementprop["module"] = "project";
	}
	if ($elementprop["module"] == "contrat") {
		$elementprop["module"] = "contract";
		$object->element = "contract";
	}
	if ($elementprop["module"] == "ficheinter") {
		$elementprop["module"] = "intervention";
	}
	$substitutionarray = getCommonSubstitutionArray($langs, 1, null, $object, array("object", $elementprop["module"]));
	$texthelp = $langs->trans("AIPromptExtrafieldDesc").'<br><br>';
	$texthelp .= $langs->trans("FollowingConstantsWillBeSubstituted").'<br><small>';
	foreach ($substitutionarray as $key => $val) {
		$texthelp .= $key.' -> '.$val.'<br>';
	}
	$texthelp .= '</small>';
	echo $form->textwithpicto($langs->trans("AIPromptExtrafield"), $texthelp, 1, 'help', 'valignmiddle', 0, 3, 'abc') }}</td>
<td class="valeur"><textarea name="ai_prompt" id="ai_prompt" class="quatrevingtpercent" rows="{{ ROWS_4 ?>">{{ $aiprompt) }}</textarea></td></tr>

<!-- Default Value (at sql setup level) -->
<tr class="extra_default_value"><td>{{ $langs->trans("DefaultValue").' ('.$langs->trans("Database").')' }}</td><td class="valeur"><input id="default_value" type="text" name="default_value" class="minwidth200" value="{{ dol_escape_htmltag($default) }}"></td></tr>

<!-- Unique -->
<tr class="extra_unique"><td>{{ $langs->trans("Unique") }}</td><td class="valeur"><input id="unique" type="checkbox" name="unique"{{ $unique ? ' checked' : '' }}></td></tr>

<!-- Required -->
<tr class="extra_required"><td>{{ $langs->trans("Mandatory") }}</td><td class="valeur"><input id="required" type="checkbox" name="required"{{ $required ? ' checked' : '' }}></td></tr>

<!-- Always editable -->
<tr class="extra_alwayseditable"><td>{{ $form->textwithpicto($langs->trans("AlwaysEditable"), $langs->trans("EditableWhenDraftOnly")) }}</td><td class="valeur"><input id="alwayseditable" type="checkbox" name="alwayseditable"{{ $alwayseditable ? ' checked' : '' }}></td></tr>

<!-- Empty on clone -->
<tr class="extra_emptyonclone"><td>{{ $form->textwithpicto($langs->trans("EmptyOnClone"), $langs->trans("EmptyOnCloneDesc")) }}</td><td class="valeur"><input id="emptyonclone" type="checkbox" name="emptyonclone"{{ $emptyonclone ? ' checked' : '' }}></td></tr>

<!-- Permission to edit -->
<tr class="extra_perms"><td>{{ $form->textwithpicto($langs->trans("PermissionOnField"), $langs->trans("PermissionToEditField")) }}</td><td class="valeur"><input id="perms" class="minwidth200" type="text" name="perms" value="{{ $perms }}"></td></tr>

<!-- Visibility -->
<tr><td class="extra_list">{{ $form->textwithpicto($langs->trans("Visibility"), $langs->trans("VisibleDesc").'<br><br>'.$langs->trans("ItCanBeAnExpression")) }}
</td><td class="valeur"><input id="list" class="width50" type="text" name="list" value="{{ $list != '' ? $list : '1' }}"></td></tr>

<!-- Visibility for PDF-->
<tr><td class="extra_pdf">{{ $form->textwithpicto($langs->trans("DisplayOnPdf"), $langs->trans("DisplayOnPdfDesc")) }}
</td><td class="valeur"><input id="printable" class="width50" type="text" name="printable" value="{{ dol_escape_htmltag((string) $printable) }}"></td></tr>

<!-- Can be summed -->
<tr class="extra_totalizable"><td>{{ $form->textwithpicto($langs->trans("Totalizable"), $langs->trans("TotalizableDesc")) }}</td><td class="valeur"><input id="totalizable" type="checkbox" name="totalizable"{{ $totalizable ? ' checked' : '' }}></td></tr>

<!-- Css edit -->
<tr class="extra_css"><td>{{ $form->textwithpicto($langs->trans("CssOnEdit"), $langs->trans("HelpCssOnEditDesc")) }}</td><td class="valeur"><input id="css" type="text" class="minwidth200" name="css" value="{{ $css ?>"></td></tr>

<!-- Css view -->
<tr class="extra_cssview"><td>{{ $form->textwithpicto($langs->trans("CssOnView"), $langs->trans("HelpCssOnViewDesc")) }}</td><td class="valeur"><input id="cssview" class="minwidth200" type="text" name="cssview" value="{{ $cssview }}"></td></tr>

<!-- Css list -->
<tr class="extra_csslist"><td>{{ $form->textwithpicto($langs->trans("CssOnList"), $langs->trans("HelpCssOnListDesc")) }}</td><td class="valeur"><input id="csslist" class="minwidth200" type="text" name="csslist" value="{{ $csslist }}"></td></tr>

<!-- Help tooltip -->
<tr class="help"><td>{{ $form->textwithpicto($langs->trans("HelpOnTooltip"), $langs->trans("HelpOnTooltipDesc")) }}</td><td class="valeur"><input id="help" class="quatrevingtpercent" type="text" name="help" value="{{ dol_escape_htmltag($help) }}"></td></tr>

<?php if (isModEnabled('multicompany')) { ?>
	<!-- Multicompany entity -->
	<tr><td>{{ $langs->trans("AllEntities") }}</td><td class="valeur"><input id="entitycurrentorall" type="checkbox" name="entitycurrentorall"{{ empty($entitycurrentorall) ? ' checked' : '' }}></td></tr>
<?php } ?>

<!-- Show Enabled property when value is not a common value -->
<?php if ($enabled != '1') { ?>
	<tr class="help"><td>{{ $form->textwithpicto($langs->trans("EnabledCondition"), $langs->trans("EnabledConditionHelp")) }}</td><td class="valeur">
	{{ dol_escape_htmltag((string) $enabled) }}
<?php } ?>
</td></tr>

</table>

{!! dol_get_fiche_end() !!}

<div class="center"><input type="submit" name="button" class="button button-save" value="{{ $langs->trans("Save") }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" name="button" class="button button-cancel" value="{{ $langs->trans("Cancel") }}"></div>

</form>

<!-- END PHP TEMPLATE admin_extrafields_edit.tpl.php -->
