{{--
/* Copyright (C) 2010-2012Regis Houssin<regis.houssin@inodbox.com>
 * Copyright (C) 2010-2014Laurent Destailleur<eldy@users.sourceforge.net>
 * Copyright (C) 2012-2013Christophe Battarel<christophe.battarel@altairis.fr>
 * Copyright (C) 2012       Cédric Salvador     <csalvador@gpcsolutions.fr>
 * Copyright (C) 2014Florian Henry<florian.henry@open-concept.pro>
 * Copyright (C) 2014       Raphaël Doursenaud  <rdoursenaud@gpcsolutions.fr>
 * Copyright (C) 2015-2016Marcos García<marcosgdf@gmail.com>
 * Copyright (C) 2018-2025  Frédéric France         <frederic.france@free.fr>
 * Copyright (C) 2018Ferran Marcet<fmarcet@2byte.es>
 * Copyright (C) 2024Vincent Maury<vmaury@timgroup.fr>
 * Copyright (C) 2024-2025MDW<mdeweerd@users.noreply.github.com>
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
--}}

@php
require_once DOL_DOCUMENT_ROOT."/product/class/html.formproduct.class.php";

global $forceall, $forcetoshowtitlelines, $filtertype;

if (empty($forceall)) {
$forceall = 0;
}

if (empty($filtertype)) {
$filtertype = 0;
}
if (!empty($object->element) && $object->element == 'contrat' && !getDolGlobalString('STOCK_SUPPORT_SERVICES')) {
$filtertype = -1;
}

$formproduct = new FormProduct($object->db);

// Define colspan for the button 'Add'
$colspan = 3; // Columns: total ht + col edit + col delete

// Lines for extrafield
$objectline = new BOMLine($this->db);

$nolinesbefore = (count($this->lines) == 0 || $forcetoshowtitlelines);
$coldisplay = 0;
@endphp

<!-- BEGIN BLADE TEMPLATE bom/tpl/objectline_create -->

@if($nolinesbefore)
<tr class="liste_titre nodrag nodrop bg-gray-100 dark:bg-gray-800">
@if(getDolGlobalString('MAIN_VIEW_LINE_NUMBER'))
<td class="linecolnum center"></td>
@endif
<td class="linecoldescription minwidth500imp">
<div id="add"></div><span class="hideonsmartphone">{{ $langs->trans('AddNewLine') }}</span>
</td>
<td class="linecolqty right">{{ $langs->trans('Qty') }}</td>

@if($filtertype != 1)
@if(getDolGlobalInt('PRODUCT_USE_UNITS'))
<td class="linecoluseunit left">
<span id="title_units">
{{ $langs->trans('Unit') }}
</span>
</td>
@endif
@else
<td class="linecolunit left">{!! $form->textwithpicto($langs->trans('Unit'), '') !!}</td>
@endif

@if($filtertype != 1 || getDolGlobalString('STOCK_SUPPORTS_SERVICES'))
<td class="linecolqtyfrozen right">{!! $form->textwithpicto($langs->trans('QtyFrozen'), $langs->trans("QuantityConsumedInvariable")) !!}</td>
<td class="linecoldisablestockchange right">{!! $form->textwithpicto($langs->trans('DisableStockChange'), $langs->trans('DisableStockChangeHelp')) !!}</td>
<td class="linecollost right">{!! $form->textwithpicto($langs->trans('ManufacturingEfficiency'), $langs->trans('ValueOfMeansLoss')) !!}</td>
@endif

@if($filtertype == 1 && isModEnabled('workstation'))
<td class="linecolworkstation">{!! $form->textwithpicto($langs->trans('Workstation'), '') !!}</td>
@endif

<td class="linecoltotalcost right">{!! $form->textwithpicto($langs->trans('TotalCost'), '') !!}</td>
<td class="linecoledit" colspan="{{ $colspan }}">&nbsp;</td>
</tr>
@endif

<tr class="pair nodrag nodrop nohoverpair{{ ($nolinesbefore || $object->element == 'contrat') ? '' : ' liste_titre_create' }}">
@if(getDolGlobalString('MAIN_VIEW_LINE_NUMBER'))
@php $coldisplay++; @endphp
<td class="bordertop nobottom linecolnum center"></td>
@endif

@php $coldisplay++; @endphp
<td class="bordertop nobottom linecoldescription bomline minwidth500imp">
@if(isModEnabled("product") || isModEnabled("service"))
@if($filtertype == 1)
{{ $langs->trans("Service") }}
@else
{{ $langs->trans("Product") }}
@endif

<span class="prod_entry_mode_predef nowraponall">
@php
$statustoshow = -1;
if (getDolGlobalString('ENTREPOT_EXTRA_STATUS')) {
echo $form->select_produits(GETPOSTINT('idprod'), (($filtertype == 1) ? 'idprodservice' : 'idprod'), $filtertype, getDolGlobalInt('PRODUIT_LIMIT_SIZE'), 0, $statustoshow, 2, '', 1, array(), 0, '1', 0, 'maxwidth500 widthcentpercentminusx', 0, 'warehouseopen,warehouseinternal', GETPOST('combinations', 'array:alphanohtml'), 1);
} else {
echo $form->select_produits(GETPOSTINT('idprod'), (($filtertype == 1) ? 'idprodservice' : 'idprod'), $filtertype, getDolGlobalInt('PRODUIT_LIMIT_SIZE'), 0, $statustoshow, 2, '', 1, array(), 0, '1', 0, 'maxwidth500 widthcentpercentminusx', 0, '', GETPOST('combinations', 'array:alphanohtml'), 1);
}
$urltocreateproduct = DOL_URL_ROOT.'/product/card.php?action=create'.(($filtertype == 1) ? '&leftmenu=service&type=1' : '&leftmenu=product&type=0').'&backtopage='.urlencode($_SERVER["PHP_SELF"].'?id='.$object->id);
@endphp
<a href="{{ $urltocreateproduct }}"><span class="fa fa-plus-circle valignmiddle paddingleft" title="{{ $langs->trans("AddProduct") }}"></span></a>
</span>
@endif

@if(getDolGlobalString('BOM_SUB_BOM') && $filtertype != 1)
<br><span class="opacitymedium">{{ $langs->trans("or") }}</span><br>{{ $langs->trans("BOM") }}
{!! $form->select_bom('', 'bom_id', 0, 1, 0, '1', '', '1') !!}
@endif

@if(is_object($objectline))
@php
$temps = $objectline->showOptionals($extrafields, 'create', array(), '', '', '1', 'line');
@endphp
@if(!empty($temps))
<div style="padding-top: 10px" id="extrafield_lines_area_create" name="extrafield_lines_area_create">
{!! $temps !!}
</div>
@endif
@endif
</td>

@php $coldisplay++; @endphp
<td class="bordertop nobottom linecolqty right">
<input type="text" size="2" name="qty" id="qty" class="flat right w-16 px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ GETPOSTISSET("qty") ? GETPOST("qty", 'alpha', 2) : 1 }}">
</td>

@if($filtertype != 1)
@if(getDolGlobalInt('PRODUCT_USE_UNITS'))
@php $coldisplay++; @endphp
<td class="nobottom linecoluseunit"></td>
@endif
@else
@php
$coldisplay++;
require_once DOL_DOCUMENT_ROOT.'/core/class/cunits.class.php';
$cUnit = new CUnits($this->db);
$fk_unit_default = $cUnit->getUnitFromCode('h', 'short_label', 'time');
@endphp
<td class="bordertop nobottom nowrap linecolunit">
{!! $formproduct->selectMeasuringUnits("fk_unit", "time", $fk_unit_default, 1) !!}
</td>
@endif

@if($filtertype != 1 || getDolGlobalString('STOCK_SUPPORTS_SERVICES'))
@php $coldisplay++; @endphp
<td class="bordertop nobottom linecolqtyfrozen right">
<input type="checkbox" name="qty_frozen" id="qty_frozen" class="flat right" value="1"{{ GETPOST("qty_frozen", 'alpha') ? ' checked="checked"' : '' }}>
</td>

@php $coldisplay++; @endphp
<td class="bordertop nobottom linecoldisablestockchange right">
<input type="checkbox" name="disable_stock_change" id="disable_stock_change" class="flat right" value="1"{{ GETPOST("disable_stock_change", 'alpha') ? ' checked="checked"' : '' }}>
</td>

@php $coldisplay++; @endphp
<td class="bordertop nobottom nowrap linecollost right">
<input type="text" size="2" name="efficiency" id="efficiency" class="flat right w-16 px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ ((GETPOSTISSET("efficiency") && $action == 'addline') ? GETPOST("efficiency", 'alpha') : 1) }}">
</td>
@endif

@if($filtertype == 1 && isModEnabled('workstation'))
@php $coldisplay++; @endphp
<td class="bordertop nobottom nowrap linecolworkstation">
{!! $formproduct->selectWorkstations('', 'idworkstations', 1) !!}
</td>
@endif

@php $coldisplay++; @endphp
<td class="bordertop nobottom nowrap linecolcost right">&nbsp;</td>

@php $coldisplay += $colspan; @endphp
<td class="bordertop nobottom linecoledit right valignmiddle" colspan="{{ $colspan }}">
<input type="submit" class="button button-add small bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded cursor-pointer transition-colors" name="addline" id="addline" value="{{ $langs->trans('Add') }}">
</td>
</tr>

<script>
/* JQuery for product free or predefined select */
jQuery(document).ready(function() {
/* When changing predefined product, we reload list of supplier prices required for margin combo */
$("#idprod").change(function()
{
console.log("#idprod change triggered");

  /* To set focus */
  if (jQuery('#idprod').val() > 0)
{
/* focus work on a standard textarea but not if field was replaced with CKEDITOR */
jQuery('#dp_desc').focus();
/* focus if CKEDITOR */
if (typeof CKEDITOR == "object" && typeof CKEDITOR.instances != "undefined")
{
var editor = CKEDITOR.instances['dp_desc'];
   if (editor) { editor.focus(); }
}
}
});

@if($filtertype == 1)
$('#idprodservice').change(function(){
var idproduct = $(this).val();

$.ajax({
url : "{{ dol_buildpath('/bom/ajax/ajax.php', 1) }}"
,type: 'POST'
,data: {
'action': 'getDurationUnitByProduct'
,'token' : "{{ newToken() }}"
,'idproduct' : idproduct
}
}).done(function(data) {

console.log(data);
$("#fk_unit").val(data).change();
});

$.ajax({
url : "{{ dol_buildpath('/bom/ajax/ajax.php', 1) }}"
,type: 'POST'
,data: {
'action': 'getWorkstationByProduct'
,'token' :  "{{ newToken() }}"
,'idproduct' : idproduct
}
}).done(function(data) {
$('#idworkstations').val(data.defaultWk).select2();
});
});
@endif
});
</script>

<!-- END BLADE TEMPLATE objectline_create -->
