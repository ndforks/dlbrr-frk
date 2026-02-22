{{-- Blade version of template --}}
{{--
/**
 * Template to show objects linked to event organization
 *
 * Variables expected:
 * - $linkedObjectBlock: array of CommonObject objects
 * - $object: CommonObject (parent object)
 * - $noMoreLinkedObjectBlockAfter: int
 * - $showImportButton: int
 * - $langs: Translate
 * - $user: User
 */
--}}

<!-- BEGIN BLADE TEMPLATE LINKEDOBJECTBLOCK -->

@php
    $langs->load("eventorganization");
    $total = 0;
@endphp

@foreach($linkedObjectBlock as $key => $objectlink)
    <tr class="oddeven hover:bg-gray-50 dark:hover:bg-gray-700">
        <td class="px-4 py-2">
            {{ $langs->trans(get_class($objectlink)) }}
        </td>
        <td class="px-4 py-2">
            {!! $objectlink->getNomUrl(1) !!}
        </td>
        <td class="px-4 py-2 text-center">
            @if(get_class($objectlink) == 'ConferenceOrBooth')
                {{ dol_trunc($objectlink->label, 20) }}
            @endif
        </td>
        <td class="px-4 py-2 text-center">
            @if(get_class($objectlink) == 'ConferenceOrBoothAttendee')
                {{ dol_print_date($objectlink->date_subscription) }}
            @else
                {{ dol_print_date($objectlink->datep) }}
            @endif
        </td>
        <td class="px-4 py-2 text-right">
            @if(get_class($objectlink) == 'ConferenceOrBoothAttendee')
                {{ price($objectlink->amount) }}
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

<!-- END BLADE TEMPLATE -->
