{{-- Blade version of template --}}
{{--
/* Copyright (C) 2010 Regis Houssin  <regis.houssin@inodbox.com>
 * Copyright (C) 2012 Philippe Grand <philippe.grand@atoo-net.com>
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
 *
 * @var string $canvas
 */
--}}

<!-- BEGIN BLADE TEMPLATE ADHERENTCARD_CREATE.TPL.PHP DEFAULT -->

{!! load_fiche_titre($this->control->tpl['title']) !!}

{!! dol_htmloutput_errors((is_numeric($object->error) ? '' : $object->error), $object->errors) !!}

{!! dol_htmloutput_errors($this->control->tpl['error'], $this->control->tpl['errors']) !!}

@php $this->control->tpl['ajax_selectcountry'] @endphp

<br>

<form method="post" name="formmember" action="{{ $_SERVER['PHP_SELF'] }}">
<input type="hidden" name="token" value="{{ newToken() }}">
<input type="hidden" name="canvas" value="{{ $canvas }}">
<input type="hidden" name="action" value="add">
@if ($this->control->tpl['company_id'])
<input type="hidden" name="socid" value="{{ $this->control->tpl['company_id'] }}">
@endif

<table class="border allwidth">

<tr>
	<td width="15%" class="fieldrequired">{{ $langs->trans("Lastname") }} / {{ $langs->trans("Label") }}</td>
	<td><input name="lastname" type="text" size="30" maxlength="80" value="{{ $this->control->tpl['name'] }}"></td>
	<td width="20%">{{ $langs->trans("Firstname") }}</td>
	<td width="25%"><input name="firstname" type="text" size="30" maxlength="80" value="{{ $this->control->tpl['firstname'] }}"></td>
</tr>

<tr>
	<td>{{ $langs->trans("Company") }}</td>
	<td colspan="3">@php $this->control->tpl['company'] @endphp</td>
</tr>

<tr>
	<td width="15%">{{ $langs->trans("UserTitle") }}</td>
	<td colspan="3">@php $this->control->tpl['select_civility'] @endphp</td>
</tr>

<tr>
	<td>{{ $langs->trans("Morphy") }}</td>
	<td colspan="3"><input name="morphy" type="text" class="minwidth200" value="{{ $this->control->tpl['select_morphy'] }}"></td>
</tr>

<tr>
	<td>{{ $langs->trans("Address") }}</td>
	<td colspan="3"><textarea class="flat" name="address" cols="70">{{ $this->control->tpl['address'] }}</textarea></td>
</tr>

<tr>
	<td>{{ $langs->trans("Zip") }} / {{ $langs->trans("Town") }}</td>
	<td colspan="3">@php $this->control->tpl['select_zip'] }}&nbsp;{!! $this->control->tpl['select_town'] @endphp</td>
</tr>

<tr>
	<td>{{ $langs->trans("Country") }}</td>
	<td colspan="3">@php $this->control->tpl['select_country'] }}{!! $this->control->tpl['info_admin'] @endphp</td>
</tr>

<tr>
	<td>{{ $langs->trans('State') }}</td>
	<td colspan="3">@php $this->control->tpl['select_state'] @endphp</td>
</tr>

<tr>
	<td>{{ $langs->trans("PhonePro") }}</td>
	<td><input name="phone_pro" type="text" size="18" maxlength="80" value="{{ $this->control->tpl['phone_pro'] }}"></td>
	<td>{{ $langs->trans("PhonePerso") }}</td>
	<td><input name="phone_perso" type="text" size="18" maxlength="80" value="{{ $this->control->tpl['phone_perso'] }}"></td>
</tr>

<tr>
	<td>{{ $langs->trans("PhoneMobile") }}</td>
	<td><input name="phone_mobile" type="text" size="18" maxlength="80" value="{{ $this->control->tpl['phone_mobile'] }}"></td>
</tr>

<tr>
	<td>{{ $langs->trans("Email") }}</td>
	<td colspan="3"><input name="email" type="text" class="minwidth200" maxlength="80" value="{{ $this->control->tpl['email'] }}"></td>
</tr>

<tr>
	<td>{{ $langs->trans("ContactVisibility") }}</td>
	<td colspan="3">@php $this->control->tpl['select_visibility'] @endphp</td>
</tr>

<tr>
	<td class="tdtop">{{ $langs->trans("Note") }}</td>
	<td colspan="3" class="tdtop"><textarea name="note" cols="70" rows="{{ ROWS_3 }}">{{ $this->control->tpl['note'] }}</textarea></td>
</tr>

<tr>
	<td class="center" colspan="4"><input type="submit" class="button" value="{{ $langs->trans('Add') }}"></td>
</tr>

</table><br>

</form>

<!-- END BLADE TEMPLATE -->
