{{--
/* Copyright (C) 2010-2013	Regis Houssin		<regis.houssin@inodbox.com>
 * Copyright (C) 2010-2011	Laurent Destailleur	<eldy@users.sourceforge.net>
 * Copyright (C) 2012-2013	Christophe Battarel	<christophe.battarel@altairis.fr>
 * Copyright (C) 2012       Cédric Salvador     <csalvador@gpcsolutions.fr>
 * Copyright (C) 2012-2014  Raphaël Doursenaud  <rdoursenaud@gpcsolutions.fr>
 * Copyright (C) 2013		    Florian Henry		<florian.henry@open-concept.pro>
 * Copyright (C) 2017		    Juanjo Menent		<jmenent@2byte.es>
 * Copyright (C) 2024-2025	MDW					<mdeweerd@users.noreply.github.com>
 * Copyright (C) 2024       Frédéric France         <frederic.france@free.fr>
 * Copyright (C) 2025		    Nick Fragoulis
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
 * $forceall (0 by default, 1 for supplier invoices/orders)
 * $element     (used to test $user->hasRight($element, 'creer'))
 * $permtoedit  (used to replace test $user->hasRight($element, 'creer'))
 * $inputalsopricewithtax (0 by default, 1 to also show column with unit price including tax)
 * $disableedit, $disablemove, $disableremove
 *
 * $type, $text, $description, $line
 */
--}}

@php
global $filtertype;
if (empty($filtertype)) {
	$filtertype = 0;
}

global $forceall, $senderissupplier, $inputalsopricewithtax, $outputalsopricetotalwithtax, $langs;

if (empty($dateSelector)) {
	$dateSelector = 0;
}
if (empty($forceall)) {
	$forceall = 0;
}

// add html5 elements
$domData  = ' data-element="'.$line->element.'"';
$domData .= ' data-id="'.$line->id.'"';
$domData .= ' data-qty="'.$line->qty.'"';
$domData .= ' data-product_type="'.$line->product_type.'"';

// Lines for extrafield
$objectline = new ExpeditionLigne($object->db);

$coldisplay = 0;
@endphp

<!-- BEGIN BLADE TEMPLATE expedition/tpl/objectline_view -->
<tr id="row-{{ $line->id }}" class="drag drop oddeven hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" {!! $domData !!}>
	@if(getDolGlobalString('MAIN_VIEW_LINE_NUMBER'))
		@php $coldisplay++; @endphp
		<td class="linecolnum center">{{ $i + 1 }}</td>
	@endif

	@php
	$coldisplay++;
	$tmpproduct = new Product($object->db);
	$tmpproduct->fetch($line->fk_product);
	$tmpexpe = new Expedition($object->db);
	@endphp

	<td class="linecoldescription line minwidth300imp tdoverflowmax300">
		<div id="line_{{ $line->id }}"></div>
		@if($line->fk_product > 0)
			{!! $tmpproduct->getNomUrl(1) !!}
			 - {{ $tmpproduct->label }}
		@else
			 - {{ $line->description }}
		@endif
	</td>

	@php $coldisplay++; @endphp
	<td class="linecolqty nowrap right">
		{{ price($line->qty, 0, '', 0, 0) }}
	</td>

	@if(getDolGlobalInt('PRODUCT_USE_UNITS'))
		@php
		$coldisplay++;
		$label = measuringUnitString((int) $line->fk_unit, '', null, 1);
		@endphp
		<td class="linecoluseunit nowrap">
			@if($label !== '')
				{{ $langs->trans($label) }}
			@endif
		</td>
	@endif

	@if($this->status == 0 && $user->hasRight('expedition', 'write') && $action != 'selectlines')
		@php $coldisplay++; @endphp
		<td class="linecoledit center">
			@if(((int) $line->info_bits & 2) != 2 && empty($disableedit))
				<a class="editfielda reposition hover:text-blue-600 transition-colors" href="{{ $_SERVER["PHP_SELF"] }}?id={{ $this->id }}&action=editline&token={{ newToken() }}&lineid={{ $line->id }}">
					{!! img_edit() !!}
				</a>
			@endif
		</td>

		@php $coldisplay++; @endphp
		<td class="linecoldelete center">
			<a class="reposition hover:text-red-600 transition-colors" href="{{ $_SERVER["PHP_SELF"] }}?id={{ $this->id }}&action=deleteline&token={{ newToken() }}&lineid={{ $line->id }}">
				{!! img_delete() !!}
			</a>
		</td>

		@php $coldisplay++; @endphp
		@if($num > 1 && $conf->browser->layout != 'phone' && empty($disablemove))
			<td class="linecolmove tdlineupdown center">
				@if($i > 0)
					<a class="lineupdown hover:text-blue-600 transition-colors" href="{{ $_SERVER["PHP_SELF"] }}?id={{ $this->id }}&action=up&token={{ newToken() }}&rowid={{ $line->id }}">
						{!! img_up('default', 0, 'imgupforline') !!}
					</a>
				@endif
				@if($i < $num - 1)
					<a class="lineupdown hover:text-blue-600 transition-colors" href="{{ $_SERVER["PHP_SELF"] }}?id={{ $this->id }}&action=down&token={{ newToken() }}&rowid={{ $line->id }}">
						{!! img_down('default', 0, 'imgdownforline') !!}
					</a>
				@endif
			</td>
		@else
			<td class="{{ ($conf->browser->layout != 'phone' && empty($disablemove)) ? 'linecolmove tdlineupdown center' : 'linecolmove center' }}"></td>
		@endif
	@else
		<td colspan="3"></td>
		@php $coldisplay += 3; @endphp
	@endif

	@if($action == 'selectlines')
		<td class="linecolcheck center">
			<input type="checkbox" class="linecheckbox" name="line_checkbox[{{ $i + 1 }}]" value="{{ $line->id }}" >
		</td>
	@endif
</tr>

<!-- END BLADE TEMPLATE objectline_view -->
