{{-- Blade version of template --}}

{!! load_fiche_titre($this->control->tpl['title']) !!}

{!! dol_htmloutput_errors($this->control->tpl['error'], $this->control->tpl['errors']) !!}

@php $this->control->tpl['ajax_selectcountry'] @endphp

<br>

<form method="post" name="formmember" action="{{ $_SERVER['PHP_SELF'].'?id='.GETPOST('id', 'int') }}">
<input type="hidden" name="token" value="{{ newToken() }}">
<input type="hidden" name="canvas" value="{{ $canvas }}">
<input type="hidden" name="id" value="{{ GETPOST('id', 'int') }}">
<input type="hidden" name="action" value="update">
<input type="hidden" name="adherentid" value="@php $this->control->tpl['id'] @endphp">
<input type="hidden" name="old_name" value="@php $this->control->tpl['name'] @endphp">
<input type="hidden" name="old_firstname" value="@php $this->control->tpl['firstname'] @endphp">
@if (!empty($this->control->tpl['company_id']))
<input type="hidden" name="socid" value="@php $this->control->tpl['company_id'] @endphp">
@endif

<table class="border allwidth">

<tr>
	<td>{{ $langs->trans("Ref") }}</td>
	<td colspan="3">@php $this->control->tpl['ref'] @endphp</td>
</tr>

<tr>
	<td width="15%" class="fieldrequired">{{ $langs->trans("Lastname").' / '.$langs->trans("Label") }}</td>
	<td><input name="lastname" type="text" size="30" maxlength="80" value="@php $this->control->tpl['name'] @endphp"></td>
	<td width="20%">{{ $langs->trans("Firstname") }}</td>
	<td width="25%"><input name="firstname" type="text" size="30" maxlength="80" value="@php $this->control->tpl['firstname'] @endphp"></td>
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
	<td colspan="3"><input name="poste" type="text" class="minwidth200" maxlength="80" value="@php $this->control->tpl['select_morphy'] @endphp"></td>
</tr>

<tr>
	<td>{{ $langs->trans("Address") }}</td>
	<td colspan="3"><textarea class="flat" name="address" cols="70">@php $this->control->tpl['address'] @endphp</textarea></td>
</tr>

<tr>
	<td>{{ $langs->trans("Zip").' / '.$langs->trans("Town") }}</td>
	<td colspan="3">echo $this->control->tpl['select_zip'].'&nbsp;'.$this->control->tpl['select_town']!!}
</td>
</tr>

<tr>
	<td>{{ $langs->trans("Country") }}</td>
	<td colspan="3">@php $this->control->tpl['select_country'].$this->control->tpl['info_admin'] @endphp</td>
</tr>

<tr>
	<td>{{ $langs->trans('State') }}</td>
	<td colspan="3">@php $this->control->tpl['select_state'] @endphp</td>
</tr>

<tr>
	<td>{{ $langs->trans("PhonePro") }}</td>
	<td><input name="phone_pro" type="text" size="18" maxlength="80" value="@php $this->control->tpl['phone_pro'] @endphp"></td>
	<td>{{ $langs->trans("PhonePerso") }}</td>
	<td><input name="phone_perso" type="text" size="18" maxlength="80" value="@php $this->control->tpl['phone_perso'] @endphp"></td>
</tr>

<tr>
	<td>{{ $langs->trans("PhoneMobile") }}</td>
	<td><input name="phone_mobile" type="text" size="18" maxlength="80" value="@php $this->control->tpl['phone_mobile'] @endphp"></td>
</tr>

<tr>
	<td>{{ $langs->trans("Email") }}</td>
	<td><input name="email" type="text" class="minwidth200" maxlength="80" value="@php $this->control->tpl['email'] @endphp"></td>
</tr>

<tr>
	<td>{{ $langs->trans("ContactVisibility") }}</td>
	<td colspan="3">@php $this->control->tpl['select_visibility'] @endphp</td>
</tr>

<tr>
	<td class="tdtop">{{ $langs->trans("Note") }}</td>
	<td colspan="3" class="tdtop"><textarea name="note" cols="70" rows="{{ ROWS_3 }}">@php $this->control->tpl['note'] @endphp</textarea></td>
</tr>

<tr>
	<td>{{ $langs->trans("DolibarrLogin") }}</td>
	<td colspan="3">@php $this->control->tpl['dolibarr_user'] @endphp</td>
</tr>

<tr>
	<td colspan="4" class="center">
	<input type="submit" class="button button-save" name="save" value="{{ $langs->trans("Save") }}">&nbsp!!}
	<input type="submit" class="button button-cancel" name="cancel" value="{{ $langs->trans("Cancel") }}">
	</td>
</tr>

</table><br>

</form>

<!-- END BLADE TEMPLATE -->
