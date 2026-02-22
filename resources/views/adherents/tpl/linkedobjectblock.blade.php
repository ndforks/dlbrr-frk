{{-- Blade version of template --}}
{{--
/**
 * Template to show objects linked to member subscriptions
 *
 * Variables expected:
 * - $linkedObjectBlock: array of Subscription objects
 * - $object: CommonObject (parent object)
 * - $noMoreLinkedObjectBlockAfter: int
 * - $showImportButton: int
 * - $langs: Translate
 * - $user: User
 */
--}}

<!-- BEGIN BLADE TEMPLATE LINKEDOBJECTBLOCK -->

@php
    $langs->load("members");
    $total = 0;
@endphp

@foreach($linkedObjectBlock as $key => $objectlink)
    <tr class="oddeven hover:bg-gray-50 dark:hover:bg-gray-700">
        <td class="px-4 py-2">
            {{ $langs->trans('Subscription') }}
        </td>
        <td class="px-4 py-2 whitespace-nowrap">
            {!! $objectlink->getNomUrl(1) !!}
        </td>
        <td class="px-4 py-2 text-center"></td>
        <td class="px-4 py-2 text-center">
            {{ dol_print_date($objectlink->dateh, 'day') }}
        </td>
        <td class="px-4 py-2 text-right">
            @if($user->hasRight('adherent', 'lire'))
                @php
                    $total += $objectlink->amount;
                @endphp
                {{ price($objectlink->amount) }}
            @endif
        </td>
        <td class="px-4 py-2 text-right"></td>
        <td class="px-4 py-2 text-right">
            <a class="reposition text-red-600 hover:text-red-800 dark:text-red-400" 
               href="{{ dolBuildUrl($_SERVER['PHP_SELF'], ['id' => $object->id, 'action' => 'dellink', 'dellinkid' => $key], true) }}">
                {!! img_picto($langs->transnoentitiesnoconv('RemoveLink'), 'unlink') !!}
            </a>
        </td>
    </tr>
@endforeach

<!-- END BLADE TEMPLATE -->
