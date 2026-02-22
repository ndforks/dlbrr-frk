{{-- Blade version of template --}}

@php $titre = $langs->trans("CardProduct".$object->type); @endphp

{!! dol_get_fiche_head($head, 'card', $titre, -1, 'product') !!}

@php $object->next_prev_filter = "(te.fk_product_type:=:".((int) $object->type).")"; @endphp

@php $shownav = 1; @endphp
@if ($user->socid && !in_array('product', explode(',', getDolGlobalString('MAIN_MODULES_FOR_EXTERNAL'))))
	$shownav = 0!!}
@endif
{!! dol_banner_tab($object, 'ref', $linkback, $shownav, 'ref') !!}

{!! dol_htmloutput_errors($object->error, $object->errors) !!}

<table class="border allwidth">

<tr>
<td width="15%">{{ $langs->trans("Ref") }}</td>
<td colspan="2">{!! dol_escape_htmltag($object->ref) !!}</td>
</tr>

<tr>
<td>{{ $langs->trans("Label") }}
</td>