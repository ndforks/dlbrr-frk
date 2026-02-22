{{-- Blade version of template --}}

{!! load_fiche_titre($langs->trans("NewProduct"), '', 'product') !!}
{!! dol_get_fiche_head([]) !!}

{!! dol_htmloutput_errors((is_numeric($object->error) ? '' : $object->error), $object->errors) !!}

{!! dol_htmloutput_errors($GLOBALS['mesg'], $GLOBALS['mesgs']) !!}
<form action="{{ $_SERVER['PHP_SELF'] }}" method="post">
<input type="hidden" name="token" value="{{ newToken() }}">
<input type="hidden" name="action" value="add">
<input type="hidden" name="type" value="0">
<input type="hidden" name="canvas" value="{{ $canvas }}">
@if (!isModEnabled('stock'))
<input name="seuil_stock_alerte" type="hidden" value="0">
@endif

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

@if (isModEnabled('stock'))
<tr><td>{{ $langs->trans("StockLimit") }}</td><td>
<input name="seuil_stock_alerte" size="4" value="{{ $object->seuil_stock_alerte }}">
</td></tr>
@endif

<tr><td>{{ $langs->trans("Nature") }}</td><td>
{{ $object->finished }}
</td></tr>

<tr><td>{{ $langs->trans("Weight") }}</td><td>
<input name="weight" size="4" value="{{ $object->weight }}">
{{ $object->weight_units }}
</td></tr>

<tr><td>{{ $langs->trans("Length") }}</td><td>
<input name="size" size="4" value="{{ $object->length }}">
{{ $object->length_units }}
</td></tr>

<tr><td>{{ $langs->trans("Surface") }}</td><td>
<input name="surface" size="4" value="{{ $object->surface }}">
{{ $object->surface_units }}
</td></tr>

<tr><td>{{ $langs->trans("Volume") }}</td><td>
<input name="volume" size="4" value="{{ $object->volume }}">
{{ $object->volume_units }}
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
