{{--
/* Copyright (C) 2022       Open-Dsi				<support@open-dsi.fr>
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
 * $element     (used to test $user->hasRight('element', 'creer'))
 * $inputalsopricewithtax (0 by default, 1 to also show column with unit price including tax)
 * $outputalsopricetotalwithtax
 * $usemargins (0 to disable all margins columns, 1 to show according to margin setup)
 *
 * $type, $text, $description, $line
 */
--}}

<!-- BEGIN BLADE TEMPLATE productattributevalueline_title -->
<thead>
	<tr class="liste_titre nodrag nodrop bg-gray-100 dark:bg-gray-800">
		@if(getDolGlobalString('MAIN_VIEW_LINE_NUMBER'))
			<td class="linecolnum center">&nbsp;</td>
		@endif

		<td class="linecolref font-semibold">{{ $langs->trans('Ref') }}</td>

		<td class="linecolvalue font-semibold">{{ $langs->trans('Value') }}</td>

		<td class="linecoledit"></td>

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
<!-- END BLADE TEMPLATE productattributevalueline_title -->
