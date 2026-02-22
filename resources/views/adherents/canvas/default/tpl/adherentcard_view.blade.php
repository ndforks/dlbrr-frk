{{-- Blade version of template --}}
{{--
/* Copyright (C) 2010-2012  Regis Houssin           <regis.houssin@inodbox.com>
 * Copyright (C) 2012-2022  Philippe Grand          <philippe.grand@atoo-net.com>
 * Copyright (C) 2024       Frédéric France         <frederic.france@free.fr>
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
 * @var Adherent $object
 * @var Canvas $this
 * @var Conf $conf
 * @var Translate $langs
 * @var User $user
 *
 * @var string $canvas
 */
--}}

@php $contact = $GLOBALS['objcanvas']->control->object; @endphp

<!-- BEGIN BLADE TEMPLATE ADHERENTCARD_VIEW.TPL.PHP DEFAULT -->

@php $this->control->tpl['showhead'] @endphp

{!! dol_htmloutput_errors($this->control->tpl['error'], $this->control->tpl['errors']) !!}

@if (!empty($this->control->tpl['action_create_user']))
@php $this->control->tpl['action_create_user'] @endphp
@endif

@if (!empty($this->control->tpl['action_delete']))
@php $this->control->tpl['action_delete'] @endphp
@endif

<table class="border allwidth">

<tr>
	<td width="20%">{{ $langs->trans("Ref") }}</td>
	<td colspan="3">@php $this->control->tpl['showrefnav'] @endphp</td>
</tr>

<tr>
	<td width="20%">{{ $langs->trans("Lastname") }}</td>
	<td width="30%">{{ $this->control->tpl['name'] }}</td>
	<td width="25%">{{ $langs->trans("Firstname") }}</td>
	<td width="25%">{{ $this->control->tpl['firstname'] }}</td>
</tr>

<tr>
	<td>{{ $langs->trans("Company") }}</td>
	<td colspan="3">@php $this->control->tpl['company'] @endphp</td>
</tr>

<tr>
	<td width="15%">{{ $langs->trans("UserTitle") }}</td>
	<td colspan="3">{{ $this->control->tpl['civility'] }}</td>
</tr>

<tr>
	<td>{{ $langs->trans("Morphy") }}</td>
	<td colspan="3">{{ $this->control->tpl['select_morphy'] }}</td>
</tr>

<tr>
	<td>{{ $langs->trans("Address") }}</td>
	<td colspan="3">{{ $this->control->tpl['address'] }}</td>
</tr>

<tr>
	<td>{{ $langs->trans("Zip") }} / {{ $langs->trans("Town") }}</td>
	<td colspan="3">{{ $this->control->tpl['zip'] }}{{ $this->control->tpl['town'] }}</td>
</tr>

<tr>
	<td>{{ $langs->trans("Country") }}</td>
	<td colspan="3">{{ $this->control->tpl['country'] }}</td>
</tr>

<tr>
	<td>{{ $langs->trans('State') }}</td>
	<td colspan="3">{{ $this->control->tpl['state'] }}</td>
</tr>

<tr>
	<td>{{ $langs->trans("PhonePro") }}</td>
	<td>{{ $this->control->tpl['phone_pro'] }}</td>
	<td>{{ $langs->trans("PhonePerso") }}</td>
	<td>{{ $this->control->tpl['phone_perso'] }}</td>
</tr>

<tr>
	<td>{{ $langs->trans("PhoneMobile") }}</td>
	<td>{{ $this->control->tpl['phone_mobile'] }}</td>
</tr>

<tr>
	<td>{{ $langs->trans("EMail") }}</td>
	<td>{{ $this->control->tpl['email'] }}</td>
</tr>

<tr>
	<td>{{ $langs->trans("ContactVisibility") }}</td>
	<td colspan="3">{{ $this->control->tpl['visibility'] }}</td>
</tr>

<tr>
	<td class="tdtop">{{ $langs->trans("Note") }}</td>
	<td colspan="3">{{ $this->control->tpl['note'] }}</td>
</tr>

<tr>
	<td>{{ $langs->trans("DolibarrLogin") }}</td>
	<td colspan="3">@php $this->control->tpl['dolibarr_user'] @endphp</td>
</tr>

</table>

@php $this->control->tpl['showend'] @endphp

@if (empty($user->socid))
<div class="tabsAction">
	@if ($user->hasRight('adherent', 'creer'))
	<a class="butAction" href="{{ $_SERVER['PHP_SELF'] }}?id={{ $this->control->tpl['id'] }}&action=edit&token={{ newToken() }}&canvas={{ $canvas }}">{{ $langs->trans('Modify') }}</a>
	@endif

	@if (!$this->control->tpl['user_id'] && $user->hasRight('user', 'user', 'creer'))
	<a class="butAction" href="{{ $_SERVER['PHP_SELF'] }}?id={{ $this->control->tpl['id'] }}&action=create_user&token={{ newToken() }}&canvas={{ $canvas }}">{{ $langs->trans("CreateDolibarrLogin") }}</a>
	@endif

	@if ($user->hasRight('adherent', 'supprimer'))
	{!! dolGetButtonAction($langs->trans("Delete"), '', 'delete', $_SERVER['PHP_SELF'].'?id='.$this->control->tpl['id'].'&action=delete&token='.newToken().'&canvas='.$canvas, 'delete', $user->hasRight('adherent', 'supprimer')) !!}
	@endif
</div><br>
@endif

@php $this->control->tpl['actionstodo'] @endphp

@php $this->control->tpl['actionsdone'] @endphp

<!-- END BLADE TEMPLATE -->
