{{-- Blade version of template --}}
{{--
/**
 * Template to show objects linked to donations
 *
 * Variables expected:
 * - $linkedObjectBlock: array of Don objects
 * - $object: CommonObject (parent object)
 * - $noMoreLinkedObjectBlockAfter: int
 * - $showImportButton: int
 * - $langs: Translate
 * - $user: User
 */
--}}

<!-- BEGIN BLADE TEMPLATE LINKEDOBJECTBLOCK -->

@php
    $langs->load("donations");
    $total = 0;
    $ilink = 0;
    $lastObjectLink = null;
@endphp

@foreach($linkedObjectBlock as $key => $objectlink)
    @php
        $ilink++;
        $trclass = 'oddeven hover:bg-gray-50 dark:hover:bg-gray-700';
        if ($ilink == count($linkedObjectBlock) && empty($noMoreLinkedObjectBlockAfter) && count($linkedObjectBlock) <= 1) {
            $trclass .= ' liste_sub_total border-t-2 border-gray-300 dark:border-gray-600';
        }
        $total += $objectlink->total_ht;
        $lastObjectLink = $objectlink;
    @endphp
    
    <tr class="{{ $trclass }}">
        <td class="px-4 py-2 max-w-[125px] overflow-hidden text-ellipsis">
            {{ $langs->trans("Donation") }}
        </td>
        <td class="px-4 py-2">
            {!! $objectlink->getNomUrl(1) !!}
        </td>
        <td class="px-4 py-2 text-center"></td>
        <td class="px-4 py-2 text-center">
            {{ dol_print_date($objectlink->date, 'day') }}
        </td>
        <td class="px-4 py-2 text-right">
            {{ price($objectlink->total_ht) }}
        </td>
        <td class="px-4 py-2 text-right">
            {!! is_object($lastObjectLink) ? $lastObjectLink->getLibStatut(3) : '' !!}
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
