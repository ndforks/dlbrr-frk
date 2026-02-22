{{--
/* Copyright (C) 2022   Open-Dsi		<support@open-dsi.fr>
 * Copyright (C) 2024       Frédéric France             <frederic.france@free.fr>
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
 *
 * Need to have the following variables defined:
 * $object (invoice, order, ...)
 * $conf
 * $langs
 * $dateSelector
 * $forceall (0 by default, 1 for supplier invoices/orders)
 * $element     (used to test $user->hasRight($element, 'creer'))
 * $senderissupplier (0 by default, 1 for supplier invoices/orders)
 * $inputalsopricewithtax (0 by default, 1 to also show column with unit price including tax)
 * $outputalsopricetotalwithtax
 * $usemargins (0 to disable all margins columns, 1 to show according to margin setup)
 * $disableedit, $disablemove, $disableremove
 *
 * $text, $description, $line
 */
--}}

@php
$domData  = ' data-element="'.$line->element.'"';
$domData .= ' data-id="'.$line->id.'"';
$coldisplay = 0;
@endphp

<!-- BEGIN BLADE TEMPLATE productattributevalueline_view -->
<tr id="row-{{ $line->id }}" class="drag drop oddeven hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" {!! $domData !!}>
	@if(getDolGlobalString('MAIN_VIEW_LINE_NUMBER'))
		@php $coldisplay++; @endphp
		<td class="linecolnum center"><span class="opacitymedium">{{ $i + 1 }}</span></td>
	@endif
	
	@php $coldisplay++; @endphp
	<td class="linecolref nowrap">
		<div id="line_{{ $line->id }}"></div>
		{!! $line->ref !!}
	</td>

	@php $coldisplay++; @endphp
	<td class="linecolvalue nowrap">{!! $line->value !!}</td>
	
	@if($user->hasRight('variants', 'write') && $action != 'selectlines')
		@php $coldisplay++; @endphp
		<td class="linecoledit center width25">
			@if(empty($disableedit))
				<a class="editfielda reposition hover:text-blue-600 transition-colors" href="{{ $_SERVER["PHP_SELF"] }}?id={{ $this->id }}&action=editline&token={{ newToken() }}&lineid={{ $line->id }}#line_{{ $line->id }}">
					{!! img_edit() !!}
				</a>
			@endif
		</td>

		@php $coldisplay++; @endphp
		<td class="linecoldelete center width25">
			@if(empty($disableremove))
				<a class="reposition hover:text-red-600 transition-colors" href="{{ $_SERVER["PHP_SELF"] }}?id={{ $this->id }}&action=ask_deleteline&token={{ newToken() }}&lineid={{ $line->id }}">
					{!! img_delete() !!}
				</a>
			@endif
		</td>

		@php $coldisplay++; @endphp
		@if($num > 1 && $conf->browser->layout != 'phone' && empty($disablemove))
			<td class="linecolmove tdlineupdown center width25">
				@if($i > 0)
					<a class="lineupdown reposition hover:text-blue-600 transition-colors" href="{{ $_SERVER["PHP_SELF"] }}?id={{ $this->id }}&action=up&token={{ newToken() }}&rowid={{ $line->id }}">
						{!! img_up('default', 0, 'imgupforline') !!}
					</a>
				@endif
				@if($i < $num - 1)
					<a class="lineupdown reposition hover:text-blue-600 transition-colors" href="{{ $_SERVER["PHP_SELF"] }}?id={{ $this->id }}&action=down&token={{ newToken() }}&rowid={{ $line->id }}">
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
			<input type="checkbox" class="linecheckbox" name="line_checkbox[{{ $i + 1 }}]" value="{{ $line->id }}">
		</td>
	@endif
</tr>
<!-- END BLADE TEMPLATE productattributevalueline_view -->
