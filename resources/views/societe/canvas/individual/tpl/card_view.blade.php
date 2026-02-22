{{-- Blade version of template --}}

{{-- Copyright (C) 2010-2011 Regis Houssin <regis.houssin@inodbox.com>
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


@php $object = $GLOBALS['objcanvas']->control->object; @endphp


{!! "<!-- BEGIN BLADE TEMPLATE CARD_VIEW.TPL.PHP INDIVIDUAL -->\n" !!}

@php $head = societe_prepare_head($object); @endphp

{!! dol_get_fiche_head($head, 'card', $langs->trans("ThirdParty"), 0, 'company') !!}

@if ($this->control->tpl['error'])
	@php $this->control->tpl['error'] @endphp
@endif
@if ($this->control->tpl['action_delete'])
	@php $this->control->tpl['action_delete'] @endphp
@endif
<table class="border allwidth">

<tr>
	<td width="20%">{{ $langs->trans('Name') }}</td>
	<td colspan="3">@php $this->control->tpl['showrefnav'] @endphp</td>
</tr>

@if ($this->control->tpl['client'])
<tr>
	<td>{{ $langs->trans('CustomerCode') }}</td>
	<td colspan="3">@php $this->control->tpl['code_client'] @endphp
	@if ($this->control->tpl['checkcustomercode'] != 0)
	<span class="error">({{ $langs->trans("WrongCustomerCode") }})</span>
	@endif
	</td>
</tr>
@endif

@if ($this->control->tpl['fournisseur'])
<tr>
	<td>{{ $langs->trans('SupplierCode') }}</td>
	<td colspan="3">@php $this->control->tpl['code_fournisseur'] @endphp
	@if ($this->control->tpl['checksuppliercode'] != 0)
	<span class="error">({{ $langs->trans("WrongSupplierCode") }})</span>
	@endif
	</td>
</tr>
@endif

@if (isModEnabled('barcode'))
<tr>
	<td>{{ $langs->trans('Gencod') }}</td>
	<td colspan="3">@php $this->control->tpl['barcode'] @endphp</td>
</tr>
@endif

<tr>
	<td class="tdtop">{{ $langs->trans('Address') }}</td>
	<td colspan="3">@php $this->control->tpl['address'] @endphp</td>
</tr>

<tr>
	<td width="25%">{{ $langs->trans('Zip') }}</td>
	<td width="25%">@php $this->control->tpl['zip'] @endphp</td>
	<td width="25%">{{ $langs->trans('Town') }}</td>
	<td width="25%">@php $this->control->tpl['town'] @endphp</td>
</tr>

<tr>
	<td>{{ $langs->trans("Country") }}</td>
	<td colspan="3" class="nowrap">@php $this->control->tpl['country'] @endphp</td>
</tr>

<tr>
	<td>{{ $langs->trans('State') }}</td>
	<td colspan="3">@php $this->control->tpl['departement'] @endphp</td>
</tr>

<tr>
	<td>{{ $langs->trans('Phone') }}</td>
	<td>@php $this->control->tpl['phone'] @endphp</td>
	<td>{{ $langs->trans('PhoneMobile') }}</td>
	<td>@php $this->control->tpl['phone_mobile'] @endphp</td>
	<td>{{ $langs->trans('Fax') }}</td>
	<td>@php $this->control->tpl['fax'] @endphp</td>
</tr>

<tr>
	<td>{{ $langs->trans('EMail') }}</td>
	<td>@php $this->control->tpl['email'] @endphp</td>
	<td>{{ $langs->trans('Web') }}</td>
	<td>@php $this->control->tpl['url'] @endphp</td>
</tr>

<tr>
	<td>{{ $langs->trans('VATIsUsed') }}</td>
	<td colspan="3">@php $this->control->tpl['tva_assuj'] @endphp</td>
</tr>

@if (!empty($this->control->tpl['localtax']))
	@php $this->control->tpl['localtax'] @endphp
@endif
<tr>
	<td>{{ $langs->trans("Type") }}</td>
	<td colspan="3">@php $this->control->tpl['typent'] @endphp</td>
</tr>

@if (getDolGlobalInt('MAIN_MULTILANGS'))
<tr>
	<td>{{ $langs->trans("DefaultLang") }}</td>
	<td colspan="3">@php $this->control->tpl['default_lang'] @endphp</td>
</tr>
@endif

<tr>
	<td>
	<table class="nobordernopadding allwidth">
		<tr>
			<td>{{ $langs->trans('RIB') }}</td>
			<td class="right">
			if ($user->hasRight('societe', 'creer')) {
<a href="@php DOL_URL_ROOT.'/societe/paymentmodes.php?socid='.$this->control->tpl['id'] }}">{!! $this->control->tpl['image_edit'] @endphp</a>
			@else
			&nbsp!!}
			@endif
			</td>
		</tr>
	</table>
	</td>
	<td colspan="3">@php $this->control->tpl['display_rib'] @endphp</td>
</tr>

<tr>
	<td>
	<table class="nobordernopadding allwidth">
		<tr>
			<td>{{ $langs->trans('SalesRepresentatives') }}</td>
			<td class="right">
			if ($user->hasRight('societe', 'creer')) {
<a href="@php DOL_URL_ROOT.'/societe/commerciaux.php?socid='.$this->control->tpl['id'] }}">{!! $this->control->tpl['image_edit'] @endphp</a>
			@else
			&nbsp!!}
			@endif
			</td>
		</tr>
	</table>
	</td>
	<td colspan="3">@php $this->control->tpl['sales_representatives'] @endphp</td>
</tr>

@if (isModEnabled('member'))
<tr>
	<td width="25%" valign="top">{{ $langs->trans("LinkedToDolibarrMember") }}</td>
	<td colspan="3">@php $this->control->tpl['linked_member'] @endphp</td>
</tr>
@endif

</table>

{!! dol_get_fiche_end() !!}

<div class="tabsAction">
@if ($user->hasRight('societe', 'creer'))
<a class="butAction" href="@php $_SERVER['PHP_SELF'].'?socid='.$this->control->tpl['id'].'&action=edit&token='.newToken().'&canvas='.urlencode($canvas) !!}">{{ $langs->trans("Modify") @endphp</a>
@endif

@if ($user->hasRight('societe', 'supprimer'))
@if ($conf->use_javascript_ajax)
		<span id="action-delete" class="butActionDelete">{{ $langs->trans('Delete') }}</span>
	@else
		<a class="butActionDelete" href="@php $_SERVER['PHP_SELF'].'?socid='.$this->control->tpl['id'].'&action=delete&token='.newToken().'&canvas='.urlencode($canvas) !!}">{{ $langs->trans('Delete') @endphp</a>
	@endif
@endif
</div>

<br>

<table class="allwidth"><tr><td valign="top" width="50%">
<div id="builddoc"></div>
{{--
 * Generated documents
 --}}
@php $delallowed = $user->hasRight('societe', 'creer'); @endphp

{!! $formfile->showdocuments('company', $socid, $filedir, $urlsource, $genallowed, $delallowed, '', 0, 0, 0, 28, 0, '', 0, '', $objcanvas->control->object->default_lang)!!}

</td>
<td></td>
</tr>
</table>

<br>
