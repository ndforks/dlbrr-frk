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
 * $dateSelector
 * $forceall (0 by default, 1 for supplier invoices/orders)
 * $senderissupplier (0 by default, 1 or 2 for supplier invoices/orders)
 * $inputalsopricewithtax (0 by default, 1 to also show column with unit price including tax)
 */
--}}

@php
global $forcetoshowtitlelines;

// Define colspan for the button 'Add'
$colspan = 3; // Columns: col edit + col delete + move button

// Lines for extrafield
$objectline = null;

$nolinesbefore = (count($this->lines) == 0 || $forcetoshowtitlelines);
$coldisplay = 0;
@endphp

<!-- BEGIN BLADE TEMPLATE productattributevalueline_create -->
<tr class="pair nodrag nodrop nohoverpair{{ $nolinesbefore ? '' : ' liste_titre_create' }}">
	@if(getDolGlobalString('MAIN_VIEW_LINE_NUMBER'))
		@php $coldisplay++; @endphp
		<td class="nobottom linecolnum center"></td>
	@endif
	
	@php $coldisplay++; @endphp
	<td class="nobottom linecolref">
		@php $coldisplay++; @endphp
		@if($nolinesbefore)
			{{ $langs->trans('Ref') }}: 
		@endif
		<input type="text" name="line_ref" id="line_ref" class="flat w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ GETPOSTISSET("line_ref") ? GETPOST("line_ref", 'alpha', 2) : '' }}" autofocus>
		@if(is_object($hookmanager ?? null))
			@php
			$parameters = array();
			$reshook = $hookmanager->executeHooks('formCreateValueOptions', $parameters, $object, $action);
			if (!empty($hookmanager->resPrint)) {
				print $hookmanager->resPrint;
			}
			@endphp
		@endif
	</td>

	<td class="nobottom linecolvalue">
		@php $coldisplay++; @endphp
		<input type="text" name="line_value" id="line_value" class="flat w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ GETPOSTISSET("line_value") ? GETPOST("line_value", 'alpha', 2) : '' }}">
	</td>

	<td class="nobottom linecoledit center valignmiddle" colspan="{{ $colspan }}">
		@php $coldisplay += $colspan; @endphp
		<input type="submit" class="button reposition small bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded cursor-pointer transition-colors" value="{{ $langs->trans('Add') }}" name="addline" id="addline">
	</td>
</tr>

<!-- END BLADE TEMPLATE productattributevalueline_create -->
