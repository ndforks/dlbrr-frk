{{--
/* Copyright (C) 2010-2011	Regis Houssin <regis.houssin@inodbox.com>
 * Copyright (C) 2013		Juanjo Menent <jmenent@2byte.es>
 * Copyright (C) 2014       Marcos García <marcosgdf@gmail.com>
 * Copyright (C) 2024-2025	MDW					<mdeweerd@users.noreply.github.com>
 * Copyright (C) 2025       Frédéric France     <frederic.france@free.fr>
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
 *  \file		resources/views/compta/facture/tpl/linkedobjectblock.blade.php
 *  \ingroup	facture
 *  \brief		Template to show objects linked to invoices
 */
--}}

@php
    $langs->load("bills");
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
    
    <tr class="{{ $trclass }}" data-element="{{ $objectlink->element }}" data-id="{{ $objectlink->id }}">
        <td class="linkedcol-element tdoverflowmax100">
            @switch($objectlink->type)
                @case(Facture::TYPE_REPLACEMENT)
                    {{ $langs->trans("InvoiceReplacement") }}
                    @break
                @case(Facture::TYPE_CREDIT_NOTE)
                    {{ $langs->trans("InvoiceAvoir") }}
                    @break
                @case(Facture::TYPE_DEPOSIT)
                    {{ $langs->trans("InvoiceDeposit") }}
                    @break
                @case(Facture::TYPE_PROFORMA)
                    {{ $langs->trans("InvoiceProForma") }}
                    @break
                @case(Facture::TYPE_SITUATION)
                    {{ $langs->trans("InvoiceSituation") }}
                    @break
                @default
                    {{ $langs->trans("CustomerInvoice") }}
            @endswitch
            @if (!empty($showImportButton) && getDolGlobalString('MAIN_ENABLE_IMPORT_LINKED_OBJECT_LINES'))
                <a class="objectlinked_importbtn" href="{!! $objectlink->getNomUrl(0, '', 0, 1) !!}&amp;action=selectlines&amp;token={{ newToken() }}" data-element="{{ $objectlink->element }}" data-id="{{ $objectlink->id }}"> <i class="fa fa-indent"></i> </a>
            @endif
        </td>
        <td class="linkedcol-name tdoverflowmax150">{!! $objectlink->getNomUrl(1) !!}</td>
        <td class="linkedcol-ref tdoverflowmax150" title="{{ dol_escape_htmltag($objectlink->ref_customer) }}">{{ dol_escape_htmltag($objectlink->ref_customer) }}</td>
        <td class="linkedcol-date center">{{ dol_print_date($objectlink->date, 'day') }}</td>
        <td class="linkedcol-amount right nowraponall">
            @if (!empty($objectlink) && $objectlink->element == 'facture' && $user->hasRight('facture', 'lire'))
                @if ($objectlink->status != 3)
                    @php
                        $total += $objectlink->total_ht;
                        echo price($objectlink->total_ht);
                    @endphp
                @else
                    <strike>{{ price($objectlink->total_ht) }}</strike>
                @endif
            @endif
        </td>
        <td class="linkedcol-statut right">
            @php
                $totalallpayments = 0;
                $totalcalculated = false;
                if (method_exists($objectlink, 'getSommePaiement')) {
                    $totalcalculated = true;
                    $totalallpayments += $objectlink->getSommePaiement();
                }
                if (method_exists($objectlink, 'getSumDepositsUsed')) {
                    $totalcalculated = true;
                    $totalallpayments += $objectlink->getSumDepositsUsed();
                }
                if (method_exists($objectlink, 'getSumCreditNotesUsed')) {
                    $totalcalculated = true;
                    $totalallpayments += $objectlink->getSumCreditNotesUsed();
                }
                echo $objectlink->getLibStatut(3, ($totalcalculated ? $totalallpayments : -1));
            @endphp
        </td>
        <td class="linkedcol-action right">
            <a class="reposition" href="{{ $_SERVER["PHP_SELF"] }}?id={{ $object->id }}&action=dellink&token={{ newToken() }}&dellinkid={{ $key }}">{!! img_picto($langs->transnoentitiesnoconv("RemoveLink"), 'unlink') !!}</a>
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
