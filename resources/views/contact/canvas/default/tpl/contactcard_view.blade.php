{{-- Blade template version --}}
{{-- Copyright (C) 2010-2012  Regis Houssin           <regis.houssin@inodbox.com>
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
 --}}

/**
 * @var Canvas $this
 * @var Conf $conf
 * @var Contact $object
 * @var Translate $langs
 * @var User $user
 *
 * @var string $canvas
 --}}



@php $contact = $GLOBALS['objcanvas']->control->object; @endphp

{!! "<!-- BEGIN BLADE TEMPLATE CONTACTCARD_VIEW.TPL.PHP DEFAULT -->\n"!!}
{!! $this->control->tpl['showhead'] !!}
{!! dol_htmloutput_errors($this->control->tpl['error'], $this->control->tpl['errors']) !!}

@if (!empty($this->control->tpl['action_create_user']))
	echo $this->control->tpl['action_create_user']!!}
@endif
@if (!empty($this->control->tpl['action_delete']))
	echo $this->control->tpl['action_delete']!!}
@endif
<table class="border allwidth">

<tr>
	<td width="20%">{{ $langs->trans("Ref") }}</td>
	<td colspan="3">@php $this->control->tpl['showrefnav'] @endphp</td>
</tr>

<tr>
	<td width="20%">{{ $langs->trans("Lastname") }}</td>
	<td width="30%">@php $this->control->tpl['name'] @endphp</td>
	<td width="25%">{{ $langs->trans("Firstname") }}</td>
	<td width="25%">@php $this->control->tpl['firstname'] @endphp</td>
</tr>

<tr>
	<td>{{ $langs->trans("ThirdParty") }}</td>
	<td colspan="3">@php $this->control->tpl['company'] @endphp</td>
</tr>

<tr>
	<td width="15%">{{ $langs->trans("UserTitle") }}</td>
	<td colspan="3">@php $this->control->tpl['civility'] @endphp</td>
</tr>

<tr>
	<td>{{ $langs->trans("PostOrFunction") }}</td>
	<td colspan="3">@php $this->control->tpl['poste'] @endphp</td>
</tr>

<tr>
	<td>{{ $langs->trans("Address") }}</td>
	<td colspan="3">@php $this->control->tpl['address'] @endphp</td>
</tr>

<tr>
	<td>{{ $langs->trans("Zip").' / '.$langs->trans("Town") }}</td>
	<td colspan="3">@php $this->control->tpl['zip'].$this->control->tpl['town'] @endphp</td>
</tr>

<tr>
	<td>{{ $langs->trans("Country") }}</td>
	<td colspan="3">@php $this->control->tpl['country'] @endphp</td>
</tr>

<tr>
	<td>{{ $langs->trans('State') }}</td>
	<td colspan="3">@php $this->control->tpl['departement'] @endphp</td>
</tr>

<tr>
	<td>{{ $langs->trans("PhonePro") }}</td>
	<td>@php $this->control->tpl['phone_pro'] @endphp</td>
	<td>{{ $langs->trans("PhonePerso") }}</td>
	<td>@php $this->control->tpl['phone_perso'] @endphp</td>
</tr>

<tr>
	<td>{{ $langs->trans("PhoneMobile") }}</td>
	<td>@php $this->control->tpl['phone_mobile'] @endphp</td>
	<td>{{ $langs->trans("Fax") }}</td>
	<td>@php $this->control->tpl['fax'] @endphp</td>
</tr>

<tr>
	<td>{{ $langs->trans("EMail") }}</td>
	<td>@php $this->control->tpl['email'] @endphp</td>
	@if ($this->control->tpl['nb_emailing'])
	<td class="nowrap">{{ $langs->trans("NbOfEMailingsReceived") }}</td>
	<td>@php $this->control->tpl['nb_emailing'] @endphp</td>
	@else
	<td colspan="2">&nbsp;</td>
	@endif
</tr>

<tr>
	<td>{{ $langs->trans("ContactVisibility") }}</td>
	<td colspan="3">@php $this->control->tpl['visibility'] @endphp</td>
</tr>

<tr>
	<td class="tdtop">{{ $langs->trans("Note") }}</td>
	<td colspan="3">@php $this->control->tpl['note'] @endphp</td>
</tr>

@foreach ($this->control->tpl['contact_element'] as $element)
<tr>
	<td>{{ $element['linked_element_label'] }}</td>
	<td colspan="3">{{ $element['linked_element_value'] }}</td>
</tr>
@endif

<tr>
	<td>{{ $langs->trans("DolibarrLogin") }}</td>
	<td colspan="3">@php $this->control->tpl['dolibarr_user'] @endphp</td>
</tr>

</table>
