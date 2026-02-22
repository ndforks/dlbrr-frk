{{-- Blade version of template --}}
{{--
/**
 * Template to show objects linked to purchase orders
 *
 * Variables expected:
 * - $linkedObjectBlock: array of CommandeFournisseur objects
 * - $object: CommonObject (parent object)
 * - $noMoreLinkedObjectBlockAfter: int
 * - $showImportButton: int
 * - $langs: Translate
 * - $user: User
 */
--}}

<!-- BEGIN BLADE TEMPLATE LINKEDOBJECTBLOCK -->

@php
    $langs->load("orders");
    $total = 0;
    $ilink = 0;
@endphp

@foreach($linkedObjectBlock as $key => $objectlink)
    @php
        $ilink++;
        $objectlink->fetch_thirdparty();
        
        $refSupplierWithThirdparty = $objectlink->ref_supplier ? dolPrintHTML($objectlink->ref_supplier) . '<br>' : '';
        $refSupplierWithThirdparty = '<span class="small">' . $refSupplierWithThirdparty;
        $refSupplierWithThirdparty .= $objectlink->thirdparty->getNomUrl(1);
        $refSupplierWithThirdparty .= '</span>';
        
        $trclass = 'oddeven hover:bg-gray-50 dark:hover:bg-gray-700';
        if ($ilink == count($linkedObjectBlock) && empty($noMoreLinkedObjectBlockAfter) && count($linkedObjectBlock) <= 1) {
            $trclass .= ' liste_sub_total border-t-2 border-gray-300 dark:border-gray-600';
        }
    @endphp
    
    <tr class="{{ $trclass }}">
        <td class="px-4 py-2 max-w-[125px] overflow-hidden text-ellipsis" 
            title="{{ dolPrintHTMLForAttribute($langs->trans('SupplierOrder')) }}">
            {{ dolPrintHTML($langs->trans('SupplierOrder')) }}
        </td>
        <td class="px-4 py-2">
            {!! $objectlink->getNomUrl(1) !!}
        </td>
        <td class="px-4 py-2 text-left max-w-[125px] overflow-hidden text-ellipsis" 
            title="{{ dolPrintHTMLForAttribute($objectlink->ref_supplier) }}">
            {!! $refSupplierWithThirdparty !!}
        </td>
        <td class="px-4 py-2 text-center">
            {{ dol_print_date($objectlink->date, 'day') }}
        </td>
        <td class="px-4 py-2 text-right">
            @if($user->hasRight('fournisseur', 'commande', 'lire'))
                @php
                    $total += $objectlink->total_ht;
                @endphp
                {{ price($objectlink->total_ht) }}
            @endif
        </td>
        <td class="px-4 py-2 text-right">
            {!! $objectlink->getLibStatut(3) !!}
        </td>
        <td class="px-4 py-2 text-right">
            <a class="reposition text-red-600 hover:text-red-800 dark:text-red-400" 
               href="{{ $_SERVER['PHP_SELF'] }}?id={{ $object->id }}&action=dellink&token={{ newToken() }}&dellinkid={{ $key }}">
                {!! img_picto($langs->transnoentitiesnoconv('RemoveLink'), 'unlink') !!}
            </a>
        </td>
    </tr>
@endforeach

@if(count($linkedObjectBlock) > 1)
    <tr class="liste_total {{ empty($noMoreLinkedObjectBlockAfter) ? 'liste_sub_total' : '' }} bg-gray-100 dark:bg-gray-700 font-semibold">
        <td class="px-4 py-2">{{ $langs->trans('Total') }}</td>
        <td class="px-4 py-2"></td>
        <td class="px-4 py-2 text-center"></td>
        <td class="px-4 py-2 text-center"></td>
        <td class="px-4 py-2 text-right">{{ price($total) }}</td>
        <td class="px-4 py-2 text-right"></td>
        <td class="px-4 py-2 text-right"></td>
    </tr>
@endif

<!-- END BLADE TEMPLATE -->
