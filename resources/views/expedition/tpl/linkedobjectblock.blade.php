{{-- Blade version of template --}}
{{--
/**
 * Template to show objects linked to shipments
 *
 * Variables expected:
 * - $linkedObjectBlock: array of Expedition objects
 * - $object: CommonObject (parent object)
 * - $noMoreLinkedObjectBlockAfter: int
 * - $showImportButton: int
 * - $langs: Translate
 * - $user: User
 */
--}}

<!-- BEGIN BLADE TEMPLATE LINKEDOBJECTBLOCK -->

@php
    $langs->load("sendings");
    $total = 0;
    $ilink = 0;
@endphp

@foreach($linkedObjectBlock as $key => $objectlink)
    @php
        $ilink++;
        $trclass = 'oddeven hover:bg-gray-50 dark:hover:bg-gray-700';
        if ($ilink == count($linkedObjectBlock) && empty($noMoreLinkedObjectBlockAfter) && count($linkedObjectBlock) <= 1) {
            $trclass .= ' liste_sub_total border-t-2 border-gray-300 dark:border-gray-600';
        }
    @endphp
    
    <tr class="{{ $trclass }}">
        <td class="px-4 py-2">
            {{ $langs->trans('Shipment') }}
        </td>
        <td class="px-4 py-2 max-w-[125px] overflow-hidden text-ellipsis">
            {!! $objectlink->getNomUrl(1) !!}
        </td>
        <td class="px-4 py-2 max-w-[125px] overflow-hidden text-ellipsis" 
            title="{{ dolPrintHTMLForAttribute($objectlink->ref_customer) }}">
            {{ dolPrintHTML($objectlink->ref_customer) }}
        </td>
        <td class="px-4 py-2 text-center">
            {{ dol_print_date($objectlink->date_delivery ? $objectlink->date_delivery : $objectlink->date_creation, 'day') }}
        </td>
        <td class="px-4 py-2 text-right">
            @if($user->hasRight('expedition', 'lire'))
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
            @if($object->element != 'commande')
                <a class="reposition text-red-600 hover:text-red-800 dark:text-red-400" 
                   href="{{ $_SERVER['PHP_SELF'] }}?id={{ $object->id }}&token={{ newToken() }}&action=dellink&dellinkid={{ $key }}">
                    {!! img_picto($langs->transnoentitiesnoconv('RemoveLink'), 'unlink') !!}
                </a>
            @endif
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
