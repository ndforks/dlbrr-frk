{{--
/* Copyright (C) 2010-2013	Regis Houssin		<regis.houssin@inodbox.com>
 * Copyright (C) 2010-2011	Laurent Destailleur	<eldy@users.sourceforge.net>
 * Copyright (C) 2012-2013	Christophe Battarel	<christophe.battarel@altairis.fr>
 * Copyright (C) 2012       Cédric Salvador     <csalvador@gpcsolutions.fr>
 * Copyright (C) 2012-2014  Raphaël Doursenaud  <rdoursenaud@gpcsolutions.fr>
 * Copyright (C) 2013		Florian Henry		<florian.henry@open-concept.pro>
 * Copyright (C) 2017		Juanjo Menent		<jmenent@2byte.es>
 * Copyright (C) 2024       Frédéric France         <frederic.france@free.fr>
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
 *
 * Need to have the following variables defined:
 * $object (invoice, order, ...)
 * $conf
 * $langs
 * $element     (used to test $user->hasRight($element, 'creer'))
 * $permtoedit  (used to replace test $user->hasRight($element, 'creer'))
 * $inputalsopricewithtax (0 by default, 1 to also show column with unit price including tax)
 * $outputalsopricetotalwithtax
 * $usemargins (0 to disable all margins columns, 1 to show according to margin setup)
 *
 * $type, $text, $description, $line
 */
--}}

@php
global $filtertype;
if (empty($filtertype)) {
	$filtertype = 0;
}
@endphp

<!-- BEGIN BLADE TEMPLATE bom/tpl/objectline_title -->

<thead>
	<tr class="liste_titre nodrag nodrop bg-gray-100 dark:bg-gray-800">
		@if(getDolGlobalString('MAIN_VIEW_LINE_NUMBER'))
			<td class="linecolnum center">&nbsp;</td>
		@endif

		<td class="linecoldescription bomline font-semibold">
			{{ $langs->trans('Description') }}
			@if(getDolGlobalString('BOM_SUB_BOM') && $filtertype != 1)
				 &nbsp; <a id="show_all" href="#">{!! img_picto('', 'folder-open', 'class="paddingright"') !!}{{ $langs->trans("ExpandAll") }}</a>&nbsp;&nbsp;
				<a id="hide_all" href="#">{!! img_picto('', 'folder', 'class="paddingright"') !!}{{ $langs->trans("UndoExpandAll") }}</a>&nbsp;
			@endif
		</td>

		<td class="linecolqty width100 right font-semibold">{!! $form->textwithpicto($langs->trans('Qty'), ($filtertype != 1) ? $langs->trans("QtyRequiredIfNoLoss") : '') !!}</td>

		@if($filtertype != 1)
			@if(getDolGlobalInt('PRODUCT_USE_UNITS'))
				<td class="linecoluseunit"></td>
			@endif
		@else
			<td class="linecolunit"></td>
		@endif

		@if($filtertype != 1 || getDolGlobalString('STOCK_SUPPORTS_SERVICES'))
			<td class="linecolqtyfrozen right font-semibold">{!! $form->textwithpicto($langs->trans('QtyFrozen'), $langs->trans("QuantityConsumedInvariable")) !!}</td>
			<td class="linecoldisablestockchange right font-semibold">{!! $form->textwithpicto($langs->trans('DisableStockChange'), $langs->trans('DisableStockChangeHelp')) !!}</td>
			<td class="linecolefficiency right font-semibold">{!! $form->textwithpicto($langs->trans('ManufacturingEfficiency'), $langs->trans('ValueOfMeansLoss')) !!}</td>
		@endif

		@if($filtertype == 1 && isModEnabled('workstation'))
			@if(isModEnabled('workstation'))
				<td class="linecolworkstation font-semibold">{!! img_picto('', 'workstation', 'class="pictofixedwidth"') !!}{!! $form->textwithpicto($langs->trans('DefaultWorkstation'), '') !!}</td>
			@endif
		@endif

		<td class="linecolcost right font-semibold">{!! $form->textwithpicto($langs->trans("TotalCost"), $langs->trans("BOMTotalCost")) !!}</td>

		<td class="linecoledit" style="width: 10px"></td>

		<td class="linecoldelete" style="width: 10px"></td>

		<td class="linecolmove" style="width: 10px"></td>

		@if($action == 'selectlines')
			<td class="linecolcheckall center">
				<input type="checkbox" class="linecheckboxtoggle" />
				<script>$(document).ready(function() {$(".linecheckboxtoggle").click(function() {var checkBoxes = $(".linecheckbox");checkBoxes.prop("checked", this.checked);})});</script>
			</td>
		@endif
	</tr>
</thead>

<!-- END BLADE TEMPLATE objectline_title -->
