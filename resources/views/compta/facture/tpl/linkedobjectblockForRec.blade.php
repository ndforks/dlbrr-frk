{{--
/* Copyright (C) 2010-2011	Regis Houssin <regis.houssin@inodbox.com>
 * Copyright (C) 2013		Juanjo Menent <jmenent@2byte.es>
 * Copyright (C) 2014       Marcos García <marcosgdf@gmail.com>
 * Copyright (C) 2024		MDW							<mdeweerd@users.noreply.github.com>
 * Copyright (C) 2025       Frédéric France         <frederic.france@free.fr>
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
// Protection to avoid direct call of template
if (empty($conf) || !is_object($conf)) {
	print "Error, template page can't be called as URL";
	exit(1);
}

global $user;
global $noMoreLinkedObjectBlockAfter;

$langs = $GLOBALS['langs'];
$linkedObjectBlock = $GLOBALS['linkedObjectBlock'];

$langs->load("bills");

$total = 0;
$ilink = 0;
@endphp

<!-- BEGIN BLADE TEMPLATE compta/facture/tpl/linkedobjectblockForRec.blade.php -->

@foreach ($linkedObjectBlock as $key => $objectlink)
	@php
	$ilink++;
	$trclass = 'oddeven';
	if ($ilink == count($linkedObjectBlock) && empty($noMoreLinkedObjectBlockAfter) && count($linkedObjectBlock) <= 1) {
		$trclass .= ' liste_sub_total';
	}
	@endphp
	<tr class="{{ $trclass }}">
		<td class="linkedcol-element tdoverflowmax100">{{ $langs->trans("RepeatableInvoice") }}</td>
		<td class="linkedcol-name tdoverflowmax150">{!! $objectlink->getNomUrl(1) !!}</td>
		<td class="linkedcol-ref" align="center"></td>
		<td class="linkedcol-date" align="center">{{ dol_print_date($objectlink->date_when, 'day') }}</td>
		<td class="linkedcol-amount right">
			@if ($user->hasRight('facture', 'lire'))
				@php $total += $objectlink->total_ht; @endphp
				{{ price($objectlink->total_ht) }}
			@endif
		</td>
		<td class="linkedcol-statut right">{!! $objectlink->getLibStatut(3) !!}</td>
		<td class="linkedcol-action right">
			<a class="reposition" href="{{ $_SERVER["PHP_SELF"] }}?id={{ $object->id }}&action=dellink&token={{ newToken() }}&dellinkid={{ $key }}">
				{!! img_picto($langs->transnoentitiesnoconv("RemoveLink"), 'unlink') !!}
			</a>
		</td>
	</tr>
@endforeach

@if (count($linkedObjectBlock) > 1)
	<tr class="liste_total {{ empty($noMoreLinkedObjectBlockAfter) ? 'liste_sub_total' : '' }}">
		<td>{{ $langs->trans("Total") }}</td>
		<td></td>
		<td align="center"></td>
		<td align="center"></td>
		<td class="right">{{ price($total) }}</td>
		<td class="right"></td>
		<td class="right"></td>
	</tr>
@endif

<!-- END BLADE TEMPLATE -->
