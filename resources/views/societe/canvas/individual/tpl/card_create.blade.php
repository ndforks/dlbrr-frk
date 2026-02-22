{{-- Blade version of template --}}

{{-- Copyright (C) 2010-2011 Regis Houssin       <regis.houssin@inodbox.com>
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


<!-- BEGIN BLADE TEMPLATE CARD_CREATE.TPL.PHP INDIVIDUAL -->

@php $this->control->tpl['title'] @endphp

@php $this->control->tpl['error'] @endphp

@if ($conf->use_javascript_ajax)
	@php $this->control->tpl['ajax_selecttype'] @endphp
<br>
{{ $langs->trans("ThirdPartyType") : &nbsp }}
<input type="radio" id="radiocompany" class="flat" name="private" value="0">
	{{ $langs->trans("CompanyFoundation") }} &nbsp; &nbsp!!}
<input type="radio" id="radioprivate" class="flat" name="private" value="1" checked> {{ $langs->trans("Individual") }} ({{ $langs->trans("ToCreateContactWithSameName") }}
)
<br>
<br>
@php $this->control->tpl['ajax_selectcountry'] @endphp
@endif

<form action="{{ $_SERVER['PHP_SELF'] }}" method="POST" name="formsoc">

<input type="hidden" name="action" value="add">
<input type="hidden" name="canvas" value="{{ $canvas }}">
<input type="hidden" name="token" value=" {{ newToken() }}">
<input type="hidden" name="private" value="@php $this->control->tpl['particulier'] @endphp">
@if ($this->control->tpl['auto_customercode'] || $this->control->tpl['auto_suppliercode'])
<input type="hidden" name="code_auto" value="1">
@endif

<table class="border allwidth">

<tr>
	<td><span class="fieldrequired">{{ $langs->trans('LastName') }}</span></td>
	<td><input type="text" size="30" maxlength="60" name="name" value="@php $this->control->tpl['name'] @endphp"></td>
</tr>

<tr>
	<td>{{ $langs->trans('FirstName') }}</td>
	<td><input type="text" size="30" name="firstname" value="@php $this->control->tpl['firstname'] @endphp"></td>
	<td colspan="2">&nbsp !!}</td>
</tr>

<tr>
	<td>{{ $langs->trans("UserTitle") }}</td>
	<td>@php $this->control->tpl['select_civility'] @endphp</td>
	<td colspan="2">&nbsp;</td>
</tr>

<tr>
	<td width="25%"><span class="fieldrequired">{{ $langs->trans('ProspectCustomer') }}</span></td>
	<td width="25%">@php $this->control->tpl['select_customertype'] @endphp</td>

	<td width="25%">{{ $langs->trans('CustomerCode') }}</td>
	<td width="25%">
		<table class="nobordernopadding">
			<tr>
				<td><input type="text" name="code_client" size="16" value="@php $this->control->tpl['customercode'] @endphp" maxlength="24"></td>
				<td>@php $this->control->tpl['help_customercode'] @endphp</td>
			</tr>
		</table>
	</td>
</tr>

@if ($this->control->tpl['supplier_enabled'])
<tr>
	<td><span class="fieldrequired">{{ $langs->trans('Supplier') }}</span></td>
	<td>@php $this->control->tpl['yn_supplier'] @endphp</td>
	<td>{{ $langs->trans('SupplierCode') }}</td>
	<td>
		<table class="nobordernopadding">
			<tr>
				<td><input type="text" name="code_fournisseur" size="16" value="@php $this->control->tpl['suppliercode'] @endphp" maxlength="24"></td>
				<td>@php $this->control->tpl['help_suppliercode'] @endphp</td>
			</tr>
		</table>
	</td>
</tr>

@if (count($this->control->tpl['suppliercategory']) > 0)
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
	<td><input size="6" type="text" name="zip" value="@php $this->control->tpl['zip'] }}">{!! $this->control->tpl['autofilltownfromzip'] @endphp</td>
	<td>{{ $langs->trans('Town') }}</td>
	<td><input type="text" name="town" value="@php $this->control->tpl['town'] @endphp"></td>
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
@if ($user->hasRight('societe', 'client', 'voir'))
<tr>
	<td>{{ $langs->trans("AllocateCommercial") }}</td>
	<td colspan="3">@php $this->control->tpl['select_users'] @endphp</td>
</tr>
@endif

<tr>
	<td colspan="4" class="center"><input type="submit" class="button" value="{{ $langs->trans('AddThirdParty') }}"></td>
</tr>

</table>
</form>

<!-- END BLADE TEMPLATE -->
