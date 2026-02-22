{{-- Blade version of template --}}
{{--
/**
 * Template to show objects linked to proposals
 *
 * Variables expected:
 * - $linkedObjectBlock: array of Propal objects
 * - $object: CommonObject (parent object)
 * - $noMoreLinkedObjectBlockAfter: int
 * - $showImportButton: int
 * - $langs: Translate
 * - $user: User
 */
--}}

<!-- BEGIN BLADE TEMPLATE LINKEDOBJECTBLOCK -->

@php
    $langs->load("propal");
    $linkedObjectBlock = dol_sort_array($linkedObjectBlock, 'date,ref', 'desc', 0, 0, 1);
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
    
    <tr class="{{ $trclass }}" data-element="{{ $objectlink->element }}" data-id="{{ $objectlink->id }}">
        <td class="px-4 py-2 max-w-[100px] overflow-hidden text-ellipsis">
            {{ $langs->trans('Proposal') }}
            @if(!empty($showImportButton) && getDolGlobalInt('MAIN_ENABLE_IMPORT_LINKED_OBJECT_LINES'))
                @php
                    $url = dolBuildUrl(DOL_URL_ROOT.'/comm/propal/card.php', ['id' => $objectlink->id, 'action' => 'selectlines'], true);
                @endphp
                <a class="objectlinked_importbtn" 
                   href="{{ $url }}" 
                   data-element="{{ $objectlink->element }}" 
                   data-id="{{ $objectlink->id }}">
                    <i class="fa fa-indent"></i>
                </a>
            @endif
        </td>
        <td class="px-4 py-2 max-w-[150px] overflow-hidden text-ellipsis">
            {!! $objectlink->getNomUrl(1) !!}
        </td>
        <td class="px-4 py-2 max-w-[150px] overflow-hidden text-ellipsis" 
            title="{{ dolPrintHTMLForAttribute($objectlink->ref_client) }}">
            {{ dolPrintHTML($objectlink->ref_client) }}
        </td>
        <td class="px-4 py-2 text-center">
            {{ dol_print_date($objectlink->date, 'day') }}
        </td>
        <td class="px-4 py-2 text-right">
            @if($user->hasRight('propal', 'lire'))
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
               href="{{ dolBuildUrl($_SERVER['PHP_SELF'], ['id' => $object->id, 'action' => 'dellink', 'dellinkid' => $key], true) }}">
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
