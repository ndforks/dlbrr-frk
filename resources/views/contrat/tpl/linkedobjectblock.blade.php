{{-- Blade version of template --}}
{{--
/**
 * Template to show objects linked to contracts
 *
 * Variables expected:
 * - $linkedObjectBlock: array of Contrat objects
 * - $object: CommonObject (parent object)
 * - $noMoreLinkedObjectBlockAfter: int
 * - $showImportButton: int
 * - $langs: Translate
 * - $user: User
 */
--}}

<!-- BEGIN BLADE TEMPLATE LINKEDOBJECTBLOCK -->

@php
    $langs->load("contracts");
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
            {{ $langs->trans('Contract') }}
        </td>
        <td class="px-4 py-2 whitespace-nowrap">
            {!! $objectlink->getNomUrl(1) !!}
        </td>
        <td class="px-4 py-2"></td>
        <td class="px-4 py-2 text-center">
            {{ dol_print_date($objectlink->date_contrat, 'day') }}
        </td>
        <td class="px-4 py-2 text-right whitespace-nowrap">
            @if($user->hasRight('contrat', 'lire') && !getDolGlobalString('CONTRACT_SHOW_TOTAL_OF_PRODUCT_AS_PRICE'))
                @php
                    $totalcontrat = 0;
                    foreach ($objectlink->lines as $linecontrat) {
                        $totalcontrat += $linecontrat->total_ht;
                        $total += $linecontrat->total_ht;
                    }
                @endphp
                {{ price($totalcontrat) }}
            @endif
        </td>
        <td class="px-4 py-2 text-right">
            {!! $objectlink->getLibStatut(7) !!}
        </td>
        <td class="px-4 py-2 text-right">
            <a class="reposition text-red-600 hover:text-red-800 dark:text-red-400" 
               href="{{ $_SERVER['PHP_SELF'] }}?id={{ $object->id }}&action=dellink&token={{ newToken() }}&dellinkid={{ $key }}">
                {!! img_picto($langs->transnoentitiesnoconv('RemoveLink'), 'unlink') !!}
            </a>
        </td>
    </tr>
@endforeach

<!-- END BLADE TEMPLATE -->
