{{-- Blade version of template --}}

{!! load_fiche_titre($langs->trans("NewService"), '', 'service') !!}
{!! dol_get_fiche_head([]) !!}

{!! dol_htmloutput_errors($this->control->tpl['error'], $this->control->tpl['errors']) !!}

{!! dol_htmloutput_errors($GLOBALS['mesg'], $GLOBALS['mesgs']) !!}
<form action="{{ $_SERVER['PHP_SELF'] }}" method="post">
<input type="hidden" name="token" value="{{ newToken() }}">
<input type="hidden" name="action" value="add">
<input type="hidden" name="type" value="1">
<input type="hidden" name="canvas" value="{{ $canvas }}">

<table class="border allwidth">

<tr>
<td class="fieldrequired" width="20%">{{ $langs->trans("Ref") }}</td>
<td><input name="ref" size="40" maxlength="32" value="{{ $object->ref }}">
@if ($refalreadyexists == 1)
	{{ $langs->trans("RefAlreadyExists") }}
@endif
</td></tr>

<tr>
<td class="fieldrequired">{{ $langs->trans("Label") }}</td>
<td><input name="label" size="40" value="{{ $object->label }}"></td>
</tr>

<tr>
<td class="fieldrequired">{{ $langs->trans("Status").' ('.$langs->trans("Sell").')' }}</td>
<td>{{ $form->selectarray('statut', $statutarray, $object->status) }}</td>
</tr>

<tr>
<td class="fieldrequired">{{ $langs->trans("Status").' ('.$langs->trans("Buy").')' }}</td>
<td>{{ $form->selectarray('statut_buy', $statutarray, $object->status_buy) }}</td>
</tr>

<tr><td>{{ $langs->trans("Duration") }}</td>
<td><input name="duration_value" size="6" maxlength="5" value="{{ $object->duration_value }}"> &nbsp!!}
{{ $object->duration_unit }}
</td></tr>

<tr><td class="tdtop">{{ $langs->trans("NoteNotVisibleOnBill") }}</td><td>
{{ $object->textarea_note }}
</td></tr>
</table>

<br>

@if (!$conf->global->PRODUIT_MULTIPRICES)
<table class="border allwidth">

<tr><td>{{ $langs->trans("SellingPrice") }}</td>
<td><input name="price" size="10" value="{{ $object->price }}">
	{{ $object->price_base_type }}
</td></tr>

<tr><td>{{ $langs->trans("MinPrice") }}</td>
<td><input name="price_min" size="10" value="{{ $object->price_min }}">
</td></tr>

<tr><td width="20%">{{ $langs->trans("VATRate") }}</td><td>
	{{ $object->tva_tx }}
</td></tr>

</table>

<br>
@endif

<div align="center"><input type="submit" class="button" value="{{ $langs->trans("Create") }}"></div>

</form>

<!-- END BLADE TEMPLATE -->
