{{-- Blade version of template --}}

@php
@php $titre = $langs->trans("CardProduct".$object->type); @endphp

@php

@php $linkback = '<a href="'.DOL_URL_ROOT.'/product/list.php?restore_lastsearch_values=1&type='.$object->type.'">'.$langs->trans("BackToList").'</a>'; @endphp
$object->next_prev_filter = "(te.fk_product_type:=:".((int) $object->type).")";

@php $shownav = 1;
@endphp @endphp
@if ($user->socid && !in_array('product', explode(',', getDolGlobalString('MAIN_MODULES_FOR_EXTERNAL'))))
@php
@endif
{!! dol_banner_tab($object, 'ref', $linkback, $shownav, 'ref') !!}

{!! dol_htmloutput_errors($object->error, $object->errors) !!}

<table class="border allwidth">

<tr>
<td width="15%">{{ $langs->trans("Ref") }}</td>
<td colspan="2">{{ $object->ref }}</td>
</tr>

<tr>
<td>{{ $langs->trans("Label") }}
</td>