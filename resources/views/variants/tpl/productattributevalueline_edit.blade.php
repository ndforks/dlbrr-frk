{{--
/* Copyright (C) 2022   Open-Dsi		<support@open-dsi.fr>
 * Copyright (C) 2024       Frédéric France         <frederic.france@free.fr>
 * Copyright (C) 2025		MDW						<mdeweerd@users.noreply.github.com>
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
 * $seller, $buyer
 * $dateSelector
 * $forceall (0 by default, 1 for supplier invoices/orders)
 * $senderissupplier (0 by default, 1 for supplier invoices/orders)
 * $inputalsopricewithtax (0 by default, 1 to also show column with unit price including tax)
 * $canchangeproduct (0 by default, 1 to allow to change the product if it is a predefined product)
 */
--}}

@php
// Define colspan for the button 'Add'
$colspan = 3; // Column: col edit + col delete + move button
$coldisplay = 0;
@endphp

<!-- BEGIN BLADE TEMPLATE productattributevalueline_edit -->
<tr class="oddeven tredited bg-blue-50 dark:bg-blue-900/20">
	@if(getDolGlobalString('MAIN_VIEW_LINE_NUMBER'))
		@php $coldisplay++; @endphp
		<td class="linecolnum center">{{ $i + 1 }}</td>
	@endif

	@php $coldisplay++; @endphp
	<td class="nobottom linecolref">
		<div id="line_{{ $line->id }}"></div>
		<input type="hidden" name="lineid" value="{{ $line->id }}">

		@php $coldisplay++; @endphp
		<input type="text" name="line_ref" id="line_ref" class="flat w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ GETPOSTISSET("line_ref") ? GETPOST("line_ref", 'alpha', 2) : $line->ref }}">
		@if(is_object($hookmanager ?? null))
			@php
			$parameters = array('line' => $line);
			$reshook = $hookmanager->executeHooks('formEditProductOptions', $parameters, $object, $action);
			if (!empty($hookmanager->resPrint)) {
				print $hookmanager->resPrint;
			}
			@endphp
		@endif
	</td>

	@php $coldisplay++; @endphp
	<td class="nobottom linecolvalue">
		<input type="text" name="line_value" id="line_value" class="flat w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ GETPOSTISSET("line_value") ? GETPOST("line_value", 'alpha', 2) : $line->value }}">
	</td>

	<!-- colspan for this td because it replace td for buttons+... -->
	@php $coldisplay += $colspan; @endphp
	<td class="center valignmiddle" colspan="{{ $colspan }}">
		<input type="submit" class="button buttongen marginbottomonly button-save bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded cursor-pointer transition-colors" id="savelinebutton marginbottomonly" name="save" value="{{ $langs->trans("Save") }}"><br>
		<input type="submit" class="button buttongen marginbottomonly button-cancel bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded cursor-pointer transition-colors" id="cancellinebutton" name="cancel" value="{{ $langs->trans("Cancel") }}">
	</td>
</tr>

<!-- END BLADE TEMPLATE productattributevalueline_edit -->
