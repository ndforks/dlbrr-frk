{{-- Blade version of template --}}
@php

{{-- Copyright (C) 2010      Regis Houssin       <regis.houssin@inodbox.com>
 * Copyright (C) 2010-2012 Laurent Destailleur <eldy@users.sourceforge.net>
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
 * @var CommonObject $this
 * @var DoliDB $db
 * @var FormFile $formfile
 * @var Translate $langs
 * @var User $user
 *
 * @var string $canvas
 --}}


<!-- BEGIN BLADE TEMPLATE CARD_EDIT.TPL.PHP INDIVIDUAL -->

@php $this->control->tpl['title'] @endphp

@php $this->control->tpl['error'] @endphp

@php $this->control->tpl['ajax_selectcountry'] @endphp

<form action="@php $_SERVER['PHP_SELF'].'?socid='.$this->control->tpl['id'] @endphp" method="POST" name="formsoc">
<input type="hidden" name="canvas" value="{{ $canvas }}">
@endphp
<input type="hidden" name="action" value="update">
@php

@if ($this->control->tpl['fournisseur'])
@if (count($this->control->tpl['suppliercategory']) > 0)
@endphp
<tr>
	<td>{{ $langs->trans('SupplierCategory') }}</td>
	<td colspan="3">@php $this->control->tpl['select_suppliercategory'] @endphp</td>
</tr>
@php
@endif
@endif
@if (isModEnabled('barcode'))
@endphp
<tr>
	<td>{{ $langs->trans('Gencod') }}</td>
	<td colspan="3"><input type="text" name="barcode" value="@php $this->control->tpl['barcode'] @endphp"></td>
</tr>
@endif

<tr>
	<td class="tdtop">{{ $langs->trans('Address') }}</td>
	<td colspan="3"><textarea name="address" cols="40" rows="3">@php $this->control->tpl['address'] @endphp</textarea></td>
</tr>

<tr>
	<td>{{ $langs->trans('Zip') }}</td>
	<td>@php $this->control->tpl['select_zip'] @endphp</td>
	<td>{{ $langs->trans('Town') }}</td>
	<td>@php $this->control->tpl['select_town'] @endphp</td>
</tr>

<tr>
	<td width="25%">{{ $langs->trans('Country') }}</td>
	<td colspan="3">echo $this->control->tpl['select_country']; echo $this->control->tpl['info_admin']!!}
</td>
</tr>

<tr>
	<td>{{ $langs->trans('State') }}</td>
	<td colspan="3">@php $this->control->tpl['select_state'] @endphp</td>
</tr>

<tr>
	<td>{{ $langs->trans('Phone') }}</td>
	<td><input type="text" name="phone" value="@php $this->control->tpl['phone'] @endphp"></td>
	<td>{{ $langs->trans('PhoneMobile') }}</td>
	<td><input type="text" name="phone_mobile" value="@php $this->control->tpl['phone_mobile'] @endphp"></td>
	<td>{{ $langs->trans('Fax') }}</td>
	<td><input type="text" name="fax" value="@php $this->control->tpl['fax'] @endphp"></td>
</tr>

<tr>
	<td>{!! $langs->trans('EMail').($conf->global->SOCIETE_EMAIL_MANDATORY ? '*' : '') !!}</td>
	<td><input type="text" name="email" size="32" value="@php $this->control->tpl['email'] @endphp"></td>
	<td>{{ $langs->trans('Web') }}</td>
	<td><input type="text" name="url" size="32" value="@php $this->control->tpl['url'] @endphp"></td>
</tr>

@if (getDolGlobalInt('MAIN_MULTILANGS'))
<tr>
	<td>{{ $langs->trans("DefaultLang") }}</td>
	<td colspan="3">@php $this->control->tpl['select_lang'] @endphp</td>
</tr>
@endif

<tr>
	<td>{{ $langs->trans('VATIsUsed') }}</td>
	<td colspan="3">@php $this->control->tpl['yn_assujtva'] @endphp</td>
</tr>

@if (!empty($this->control->tpl['localtax']))
	@php $this->control->tpl['localtax'] @endphp
@endif
</table>
<br>

<div class="center">
<input type="submit" class="button button-save" name="save" value="{{ $langs->trans("Save") }}">
&nbsp; &nbsp!!}
<input type="submit" class="button button-cancel" name="cancel" value="{{ $langs->trans("Cancel") }}">
</div>

</form>

<!-- END BLADE TEMPLATE -->
