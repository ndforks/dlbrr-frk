{{-- Blade version of template --}}
@if ($this->control->tpl['js_checkVatPopup'])
	@php $this->control->tpl['js_checkVatPopup'] @endphp
@endif
<form action="@php $_SERVER['PHP_SELF'].'?socid='.$this->control->tpl['id'] @endphp" method="POST" name="formsoc">
<input type="hidden" name="canvas" value="{{ $canvas }}">
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


for ($i = 1; $i <= 4; $i++) {
	if ($this->control->tpl['langprofid'.$i] != '-') {
		if ($i == 1 || $i == 3) {
			{{ '<tr>' }}
@endif
		{!! '<td>'.$this->control->tpl['langprofid'.$i].'</td>' !!}
		{!! '<td>'.$this->control->tpl['showprofid'.$i].'</td>' !!}
		if ($i == 2 || $i == 4) {
			{{ '</tr>' }}
@endif
	} else {
		if ($i == 1 || $i == 3) {
			{{ '<tr>' }}
@endif
		{{ '<td>&nbsp }}</td>'!!}
		{{ '<td>&nbsp }}</td>'!!}
		if ($i == 2 || $i == 4) {
			{{ '</tr>' }}
@endif
@endif
@endif
<tr>
	<td>{{ $langs->trans('VATIsUsed') }}</td>
	<td>@php $this->control->tpl['yn_assujtva'] @endphp</td>
	<td class="nowrap">{{ $langs->trans('VATIntra') }}</td>
	<td class="nowrap">@php $this->control->tpl['tva_intra'] @endphp</td>
</tr>

<tr>
	<td>{{ $langs->trans('Capital') }}</td>
	<td colspan="3"><input type="text" name="capital" size="10" value="@php $this->control->tpl['capital'] @endphp"> {!! $langs->trans("Currency".$conf->currency) !!}</td>
</tr>

<tr>
	<td>{{ $langs->trans('JuridicalStatus') }}</td>
	<td colspan="3">@php $this->control->tpl['select_juridicalstatus'] @endphp</td>
</tr>

<tr>
	<td>{{ $langs->trans("ThirdPartyType") }}</td>
<td>@php $this->control->tpl['select_companytype'] }} {!! $this->control->tpl['info_admin'] @endphp </td>
	<td>{{ $langs->trans("Staff") }}</td>
	<td>echo $this->control->tpl['select_workforce']; echo $this->control->tpl['info_admin']!!}
</td>
</tr>

@if (getDolGlobalInt('MAIN_MULTILANGS'))
<tr>
	<td>{{ $langs->trans("DefaultLang") }}</td>
	<td colspan="3">@php $this->control->tpl['select_lang'] @endphp</td>
</tr>
@endif
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
