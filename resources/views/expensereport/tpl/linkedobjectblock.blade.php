{{--
/* Copyright (C) 2010-2011	Regis Houssin 			<regis.houssin@inodbox.com>
 * Copyright (C) 2013		Juanjo Menent 			<jmenent@2byte.es>
 * Copyright (C) 2014       Marcos García 			<marcosgdf@gmail.com>
 * Copyright (C) 2024		MDW						<mdeweerd@users.noreply.github.com>
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

/**
 *  \file		resources/views/expensereport/tpl/linkedobjectblock.blade.php
 *  \ingroup	expensereport
 *  \brief		Template to show objects linked to expense reports
 */
--}}

@php
    $total = 0;
    $ilink = 0;
@endphp

@foreach ($linkedObjectBlock as $key => $objectlink)
    @php
        $ilink++;
        $trclass = 'oddeven';
        if ($ilink == count($linkedObjectBlock) && empty($noMoreLinkedObjectBlockAfter) && count($linkedObjectBlock) <= 1) {
            $trclass .= ' liste_sub_total';
        }
    @endphp
    
    <tr class="{{ $trclass }}">
        <td>{{ $langs->trans("ExpenseReport") }}</td>
        <td>{!! $objectlink->getNomUrl(1) !!}</td>
        <td></td>
        <td class="center">{{ dol_print_date($objectlink->date_debut, 'day') }}</td>
        <td class="right">
            @if ($user->hasRight('expensereport', 'lire'))
                @php
                    $total += $objectlink->total_ht;
                    echo price($objectlink->total_ht);
                @endphp
            @endif
        </td>
        <td class="right">{!! $objectlink->getLibStatut(3) !!}</td>
        <td class="right">
            <a class="reposition" href="{{ $_SERVER["PHP_SELF"] }}?id={{ $object->id }}&action=dellink&token={{ newToken() }}&dellinkid={{ $key }}">{!! img_picto($langs->transnoentitiesnoconv("RemoveLink"), 'unlink') !!}</a>
        </td>
    </tr>
@endforeach
