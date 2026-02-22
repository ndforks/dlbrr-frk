{{--
/* Copyright (C) 2012 Regis Houssin       <regis.houssin@inodbox.com>
 * Copyright (C) 2014 Marcos García       <marcosgdf@gmail.com>
 * Copyright (C) 2019 Laurent Destailleur <eldy@users.sourceforge.net>
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

/**
 *  \file		resources/views/reception/tpl/linkedobjectblock.blade.php
 *  \ingroup	reception
 *  \brief		Template to show objects linked to reception
 */
--}}

@php
    $langs->load("receptions");
    $linkedObjectBlock = dol_sort_array($linkedObjectBlock, 'date,ref', 'desc', 0, 0, 1);
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
        <td class="linkedcol-element tdoverflowmax125">{{ $langs->trans("Reception") }}
            @if (!empty($showImportButton) && getDolGlobalInt('MAIN_ENABLE_IMPORT_LINKED_OBJECT_LINES'))
                <a class="objectlinked_importbtn" href="{!! $objectlink->getNomUrl(0, '', 0, 1) !!}&amp;action=selectlines&amp;token={{ newToken() }}" data-element="{{ $objectlink->element }}" data-id="{{ $objectlink->id }}"> <i class="fa fa-indent"></i> </a>
            @endif
        </td>
        <td class="linkedcol-name tdoverflowmax150">{!! $objectlink->getNomUrl(1) !!}</td>
        <td class="linkedcol-ref tdoverflowmax150" title="{{ dol_escape_htmltag($objectlink->ref_supplier) }}">{{ dol_escape_htmltag($objectlink->ref_supplier) }}</td>
        <td class="linkedcol-date">{{ dol_print_date($objectlink->date_delivery, 'day') }}</td>
        <td class="linkedcol-amount right">
            @if ($user->hasRight('reception', 'lire'))
                @php
                    $total += $objectlink->total_ht;
                    echo price($objectlink->total_ht);
                @endphp
            @endif
        </td>
        <td class="linkedcol-statut right">{!! $objectlink->getLibStatut(3) !!}</td>
        <td class="linkedcol-action right">
            @if ($object->element != 'order_supplier')
                <a class="reposition" href="{{ $_SERVER["PHP_SELF"] }}?id={{ $object->id }}&action=dellink&token={{ newToken() }}&dellinkid={{ $key }}">{!! img_picto($langs->transnoentitiesnoconv("RemoveLink"), 'unlink') !!}</a>
            @endif
        </td>
    </tr>
@endforeach

@if (count($linkedObjectBlock) > 1)
    <tr class="liste_total {{ empty($noMoreLinkedObjectBlockAfter) ? 'liste_sub_total' : '' }}">
        <td>{{ $langs->trans("Total") }}</td>
        <td></td>
        <td class="center"></td>
        <td class="center"></td>
        <td class="right">{{ price($total) }}</td>
        <td class="right"></td>
        <td class="right"></td>
    </tr>
@endif
