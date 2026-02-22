{{-- Blade version of template
/* Copyright (C) 2017 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2024       Frédéric France         <frederic.france@free.fr>
 * Copyright (C) 2025		MDW						<mdeweerd@users.noreply.github.com>
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
--}}

<!-- linesalesrepresentative.blade.php -->

<tr>
    <td>
        <table class="nobordernopadding centpercent">
            <tr>
                <td>{{ $langs->trans('SalesRepresentatives') }}</td>
                @if ($action != 'editsalesrepresentatives' && $user->hasRight('societe', 'creer'))
                    <td class="right">
                        <a class="editfielda reposition" href="{{ dolBuildUrl($_SERVER['PHP_SELF'], ['action' => 'editsalesrepresentatives', 'socid' => $object->id], true) }}">
                            {!! img_edit($langs->transnoentitiesnoconv('Edit'), 1) !!}
                        </a>
                    </td>
                @endif
            </tr>
        </table>
    </td>
    <td>
        @if ($action == 'editsalesrepresentatives')
            <form method="post" action="{{ $_SERVER['PHP_SELF'] }}">
                <input type="hidden" name="action" value="set_salesrepresentatives" />
                <input type="hidden" name="token" value="{{ newToken() }}" />
                <input type="hidden" name="socid" value="{{ $object->id }}" />
                
                @php
                    $userlist = $form->select_dolusers('', '', 0, null, 0, '', '', 'default', 0, 0, '', 0, '', '', 0, 1);
                    $arrayselected = GETPOST('commercial', 'array');
                    if (empty($arrayselected)) {
                        $arrayselected = $object->getSalesRepresentatives($user, 1);
                    }
                @endphp
                
                {!! $form->multiselectarray('commercial', $userlist, $arrayselected, 0, 0, '', 0, "90%") !!}
                <input type="submit" class="button valignmiddle smallpaddingimp" value="{{ $langs->trans('Modify') }}" />
            </form>
        @else
            @php
                $listsalesrepresentatives = $object->getSalesRepresentatives($user);
                $nbofsalesrepresentative = is_array($listsalesrepresentatives) ? count($listsalesrepresentatives) : 0;
            @endphp
            
            @if ($nbofsalesrepresentative > 0)
                @php $userstatic = new User($db); @endphp
                @foreach ($listsalesrepresentatives as $val)
                    @php
                        $userstatic->id = $val['id'];
                        $userstatic->login = $val['login'];
                        $userstatic->lastname = $val['lastname'];
                        $userstatic->firstname = $val['firstname'];
                        $userstatic->status = $val['statut'];
                        $userstatic->photo = $val['photo'];
                        $userstatic->email = $val['email'];
                        $userstatic->office_phone = $val['office_phone'];
                        $userstatic->user_mobile = $val['user_mobile'];
                        $userstatic->job = $val['job'];
                        $userstatic->entity = $val['entity'];
                        $userstatic->gender = $val['gender'];
                    @endphp
                    {!! $userstatic->getNomUrl(-1, '', 0, 0, ($nbofsalesrepresentative > 1 ? 16 : (empty($conf->dol_optimize_smallscreen) ? 24 : 20))) !!}{{ ' ' }}
                @endforeach
            @endif
        @endif
    </td>
</tr>
