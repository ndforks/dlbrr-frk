{{--
/* Copyright (C) 2010-2013	Regis Houssin		<regis.houssin@inodbox.com>
 * Copyright (C) 2010-2011	Laurent Destailleur	<eldy@users.sourceforge.net>
 * Copyright (C) 2012-2013	Christophe Battarel	<christophe.battarel@altairis.fr>
 * Copyright (C) 2012       Cédric Salvador     <csalvador@gpcsolutions.fr>
 * Copyright (C) 2012-2014  Raphaël Doursenaud  <rdoursenaud@gpcsolutions.fr>
 * Copyright (C) 2013		Florian Henry		<florian.henry@open-concept.pro>
 * Copyright (C) 2017		Juanjo Menent		<jmenent@2byte.es>
 * Copyright (C) 2024-2026	MDW					<mdeweerd@users.noreply.github.com>
 * Copyright (C) 2024-2025  Frédéric France         <frederic.france@free.fr>
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
 *
 * Need to have the following variables defined:
 * $object (invoice, order, ...)
 * $conf
 * $langs
 * $forceall (0 by default, 1 for supplier invoices/orders)
 * $element     (used to test $user->hasRight($element, 'creer'))
 * $permtoedit  (used to replace test $user->hasRight($element, 'creer'))
 * $inputalsopricewithtax (0 by default, 1 to also show column with unit price including tax)
 * $disableedit, $disablemove, $disableremove
 *
 * $type, $text, $description, $line
 */
--}}
@php
require_once DOL_DOCUMENT_ROOT.'/workstation/class/workstation.class.php';

// Protection to avoid direct call of template
if (empty($object) || !is_object($object)) {
	print "Error, template page can't be called as URL";
	exit(1);
}

global $filtertype;
if (empty($filtertype)) {
	$filtertype = 0;
}

global $forceall, $senderissupplier, $inputalsopricewithtax, $outputalsopricetotalwithtax, $langs;

if (empty($forceall)) {
	$forceall = 0;
}
if (empty($senderissupplier)) {
	$senderissupplier = 0;
}
if (empty($inputalsopricewithtax)) {
	$inputalsopricewithtax = 0;
}
if (empty($outputalsopricetotalwithtax)) {
	$outputalsopricetotalwithtax = 0;
}

// add html5 elements
if ($filtertype == 1) {
	$domData  = ' data-element="'.$line->element.'service"';
} else {
	$domData  = ' data-element="'.$line->element.'"';
}

$domData .= ' data-id="'.$line->id.'"';
$domData .= ' data-qty="'.$line->qty.'"';
$domData .= ' data-product_type="'.$line->product_type.'"';

// Lines for extrafield
$objectline = new BOMLine($object->db);

$coldisplay = 0;
@endphp

<!-- BEGIN BLADE TEMPLATE bom/tpl/objectline_view.blade.php -->
<tr id="row-{{ $line->id }}" class="drag drop oddeven" {!! $domData !!}>
@if (getDolGlobalString('MAIN_VIEW_LINE_NUMBER'))
	@php $coldisplay++; @endphp
	<td class="linecolnum center">{{ $i + 1 }}</td>
@endif

@php
$coldisplay++;
$tmpproduct = new Product($object->db);
$tmpproduct->fetch($line->fk_product);
$tmpbom = new BOM($object->db);
$res = $tmpbom->fetch((int) $line->fk_bom_child);
@endphp

<td class="linecoldescription bomline minwidth300imp tdoverflowmax300">
	<div id="line_{{ $line->id }}"></div>
	@if ($tmpbom->id > 0)
		{!! $tmpproduct->getNomUrl(1) !!}
		{{ $langs->trans("or") }}
		{!! $tmpbom->getNomUrl(1) !!}
		<a class="collapse_bom" id="collapse-{{ $line->id }}" href="#">
			@if (!getDolGlobalString('BOM_SHOW_ALL_BOM_BY_DEFAULT'))
				{!! img_picto('', 'folder') !!}
			@else
				{!! img_picto('', 'folder-open') !!}
			@endif
		</a>
	@else
		{!! $tmpproduct->getNomUrl(1) !!}
		- {{ $tmpproduct->label }}
	@endif

	@if (!empty($extrafields))
		@php
		$temps = $line->showOptionals($extrafields, 'view', array(), '', '', '1', 'line');
		@endphp
		@if (!empty($temps))
			<div style="padding-top: 10px" id="extrafield_lines_area_{{ $line->id }}" name="extrafield_lines_area_{{ $line->id }}">
				{!! $temps !!}
			</div>
		@endif
	@endif
</td>

@php $coldisplay++; @endphp
<td class="linecolqty nowrap right">
	{{ price($line->qty, 0, '', 0, 0) }}
</td>

@if ($filtertype != 1)
	@if (getDolGlobalInt('PRODUCT_USE_UNITS'))
		@php
		$label = measuringUnitString((int) $line->fk_unit, '', null, 1);
		@endphp
		<td class="linecoluseunit nowrap">
			@if ($label !== '')
				{{ $langs->trans($label) }}
			@endif
		</td>
	@endif
@else
	@php
	$coldisplay++;
	$unitLabel = '';
	if (!empty($line->fk_unit)) {
		require_once DOL_DOCUMENT_ROOT.'/core/class/cunits.class.php';
		$unit = new CUnits($this->db);
		$unit->fetch($line->fk_unit);
		$unitLabel = isset($unit->label) ? "&nbsp;".$langs->trans(ucwords((string) $unit->label))."&nbsp;" : '';
	}
	@endphp
	<td class="linecolunit nowrap">{!! $unitLabel !!}</td>
@endif

@if ($filtertype != 1 || getDolGlobalString('STOCK_SUPPORTS_SERVICES'))
	@php $coldisplay++; @endphp
	<td class="linecolqtyfrozen nowrap right">
		@if ($line->qty_frozen)
			{{ yn($line->qty_frozen) }}
		@endif
	</td>

	@php $coldisplay++; @endphp
	<td class="linecoldisablestockchange nowrap right">
		@if ($line->disable_stock_change)
			{{ yn($line->disable_stock_change) }}
		@endif
	</td>

	@php $coldisplay++; @endphp
	<td class="linecolefficiency nowrap right">
		{{ $line->efficiency }}
	</td>
@endif

@if ($filtertype == 1 && isModEnabled('workstation'))
	@php
	$coldisplay++;
	$workstation = new Workstation($object->db);
	$res = $workstation->fetch($line->fk_default_workstation);
	$workstationHtml = '';
	if ($res > 0) {
		$unit = new CUnits($object->db);
		$fk_defaultUnit = $unit->getUnitFromCode('h', 'short_label', 'time');
		$nbPlannedHour = $unit->unitConverter($line->qty, $line->fk_unit, $fk_defaultUnit);
		$line->total_cost = 0;
		if ($workstation->thm_machine_estimated) {
			$line->total_cost += $nbPlannedHour * $workstation->thm_machine_estimated;
		}
		if ($workstation->thm_operator_estimated) {
			$line->total_cost += $nbPlannedHour * $workstation->thm_operator_estimated;
		}
		$workstationHtml = $workstation->getNomUrl(1);
	}
	@endphp
	<td class="linecolworkstation nowrap">{!! $workstationHtml !!}</td>
@endif

@php
$total_cost = 0;
$tmpbom->calculateCosts();
$line->qty = (float) $line->qty;
if ($tmpbom->id > 0) $line->qty /= $tmpbom->qty;
$coldisplay++;
@endphp

<td id="costline_{{ $line->id }}" class="linecolcost nowrap right">
	@if (!empty($line->fk_bom_child))
		<span class="amount">{{ price(price2num($tmpbom->total_cost * $line->qty, 'MT')) }}</span>
	@else
		<span class="amount">{{ price($line->total_cost) }}</span>
	@endif
</td>

@if ($this->status == 0 && $user->hasRight('bom', 'write') && $action != 'selectlines')
	@php $coldisplay++; @endphp
	<td class="linecoledit center">
		@if (((int) $line->info_bits & 2) != 2 && empty($disableedit))
			<a class="editfielda reposition" href="{{ $_SERVER["PHP_SELF"] }}?id={{ $this->id }}&action=editline&token={{ newToken() }}&lineid={{ $line->id }}">
				{!! img_edit() !!}
			</a>
		@endif
	</td>

	@php $coldisplay++; @endphp
	<td class="linecoldelete center">
		@if (empty($disableremove))
			<a class="reposition" href="{{ $_SERVER["PHP_SELF"] }}?id={{ $this->id }}&action=deleteline&token={{ newToken() }}&lineid={{ $line->id }}">
				{!! img_delete() !!}
			</a>
		@endif
	</td>

	@php
	$coldisplay++;
	$canMove = ($num > 1 && $conf->browser->layout != 'phone' && empty($disablemove));
	@endphp
	@if ($canMove)
		<td class="linecolmove tdlineupdown center">
			@if ($i > 0)
				<a class="lineupdown" href="{{ $_SERVER["PHP_SELF"] }}?id={{ $this->id }}&action=up&token={{ newToken() }}&rowid={{ $line->id }}">
					{!! img_up('default', 0, 'imgupforline') !!}
				</a>
			@endif
			@if ($i < $num - 1)
				<a class="lineupdown" href="{{ $_SERVER["PHP_SELF"] }}?id={{ $this->id }}&action=down&token={{ newToken() }}&rowid={{ $line->id }}">
					{!! img_down('default', 0, 'imgdownforline') !!}
				</a>
			@endif
		</td>
	@else
		<td class="{{ ($conf->browser->layout != 'phone' && empty($disablemove)) ? 'linecolmove tdlineupdown center' : 'linecolmove center' }}"></td>
	@endif
@else
	@php $coldisplay += 3; @endphp
	<td colspan="3"></td>
@endif

@if ($action == 'selectlines')
	<td class="linecolcheck center">
		<input type="checkbox" class="linecheckbox" name="line_checkbox[{{ $i + 1 }}]" value="{{ $line->id }}">
	</td>
@endif
</tr>

@php
// Select of all the sub-BOM lines
$sql = 'SELECT rowid, fk_bom_child, fk_product, qty FROM '.MAIN_DB_PREFIX.'bom_bomline AS bl';
$sql .= ' WHERE fk_bom ='. (int) $tmpbom->id;
$resql = $object->db->query($sql);

if ($resql) {
	$j = 0; // sub bom line number
	while ($obj = $object->db->fetch_object($resql)) {
		$sub_bom_product = new Product($object->db);
		$sub_bom_product->fetch($obj->fk_product);

		$sub_bom = new BOM($object->db);
		if (!empty($obj->fk_bom_child)) {
			$sub_bom->fetch($obj->fk_bom_child);
		}

		$sub_bom_line = new BOMLine($object->db);
		$sub_bom_line->fetch($obj->rowid);
@endphp

		@if (!getDolGlobalString('BOM_SHOW_ALL_BOM_BY_DEFAULT'))
			<tr style="display:none" class="sub_bom_lines" parentid="{{ $line->id }}">
		@else
			<tr class="sub_bom_lines" parentid="{{ $line->id }}">
		@endif

		@if (getDolGlobalString('MAIN_VIEW_LINE_NUMBER'))
			@php $coldisplay++; @endphp
			<td class="linecolnum center">{{ $i + 1 }}.{{ $j + 1 }}</td>
		@endif

		@php
		$productHtml = '';
		if (!empty($obj->fk_bom_child)) {
			$productHtml = $sub_bom_product->getNomUrl(1);
			$productHtml .= ' '.$langs->trans('or').' ';
			$productHtml .= $sub_bom->getNomUrl(1);
		} else {
			$productHtml = $sub_bom_product->getNomUrl(1);
		}
		@endphp
		<td style="padding-left: 5%" id="sub_bom_product_{{ $sub_bom_line->id }}">{!! $productHtml !!}</td>

		@php
		$label = $sub_bom_product->getLabelOfUnit('long', $langs);
		@endphp
		@if ($sub_bom_line->qty_frozen > 0)
			<td class="linecolqty nowrap right" id="sub_bom_qty_{{ $sub_bom_line->id }}">
				{{ price(price2num($sub_bom_line->qty, 'MS'), 0, '', 0, 0) }}
			</td>
			@if (getDolGlobalString('PRODUCT_USE_UNITS'))
				<td class="linecoluseunit nowrap left">{{ $label }}</td>
			@endif
			<td class="linecolqtyfrozen nowrap right" id="sub_bom_qty_frozen_{{ $sub_bom_line->id }}">
				{{ $langs->trans('Yes') }}
			</td>
		@else
			<td class="linecolqty nowrap right" id="sub_bom_qty_{{ $sub_bom_line->id }}">
				{{ price(price2num($sub_bom_line->qty * $line->qty, 'MS'), 0, '', 0, 0) }}
			</td>
			@if (getDolGlobalString('PRODUCT_USE_UNITS'))
				<td class="linecoluseunit nowrap left">{{ $label }}</td>
			@endif
			<td class="linecolqtyfrozen nowrap right" id="sub_bom_qty_frozen_{{ $sub_bom_line->id }}">&nbsp;</td>
		@endif

		<td class="linecoldisablestockchange nowrap right" id="sub_bom_stock_change_{{ $sub_bom_line->id }}">
			@if ($sub_bom_line->disable_stock_change > 0)
				{{ $sub_bom_line->disable_stock_change }}
			@else
				&nbsp;
			@endif
		</td>

		<td class="linecolefficiency nowrap right" id="sub_bom_efficiency_{{ $sub_bom_line->id }}">
			{{ $sub_bom_line->efficiency }}
		</td>

		@php
		$costHtml = '';
		if (!empty($sub_bom->id)) {
			$sub_bom->calculateCosts();
			$costHtml = '<span class="amount">'.price(price2num($sub_bom_line->qty * $line->qty * $sub_bom->unit_cost, 'MS')).'</span>';
		} elseif ($sub_bom_product->type == Product::TYPE_SERVICE && isModEnabled('workstation') && !empty($sub_bom_product->fk_default_workstation)) {
			$unit = measuringUnitString($sub_bom_line->fk_unit, '', null, 1);
			$qty = convertDurationtoHour($sub_bom_line->qty, $unit);
			$workstation = new Workstation($this->db);
			$res = $workstation->fetch($sub_bom_product->fk_default_workstation);
			if ($res > 0) {
				$sub_bom_line->total_cost = (float) price2num($qty * ($workstation->thm_operator_estimated + $workstation->thm_machine_estimated), 'MT');
			}
			$costHtml = '<span class="amount">'.price(price2num($sub_bom_line->total_cost, 'MT')).'</span>';
		} elseif ($sub_bom_product->cost_price > 0) {
			$costHtml = '<span class="amount">'.price(price2num($sub_bom_product->cost_price * $sub_bom_line->qty * $line->qty, 'MT')).'</span>';
		} elseif ($sub_bom_product->pmp > 0) {
			$costHtml = '<span class="amount">'.price(price2num($sub_bom_product->pmp * $sub_bom_line->qty * $line->qty, 'MT')).'</span>';
		} else {
			$sql_supplier_price = "SELECT MIN(price) AS min_price, quantity AS qty FROM ".MAIN_DB_PREFIX."product_fournisseur_price";
			$sql_supplier_price .= " WHERE fk_product = ". (int) $sub_bom_product->id;
			$sql_supplier_price .= " GROUP BY quantity ORDER BY quantity ASC";
			$resql_supplier_price = $object->db->query($sql_supplier_price);
			if ($resql_supplier_price) {
				$obj_price = $object->db->fetch_object($resql_supplier_price);
				if (!empty($obj_price->qty) && !empty($sub_bom_line->qty) && !empty($line->qty)) {
					$line_cost = $obj_price->min_price / $obj_price->qty * $sub_bom_line->qty * $line->qty;
				} else {
					$line_cost = $obj_price->min_price;
				}
				$costHtml = '<span class="amount">'.price2num($line_cost, 'MT').'</span>';
			}
		}
		@endphp
		<td class="linecolcost nowrap right" id="sub_bom_cost_{{ $sub_bom_line->id }}">{!! $costHtml !!}</td>

		<td></td>
		<td></td>
		<td></td>
		</tr>

@php
		$j++;
	}
}
@endphp

<!-- END BLADE TEMPLATE objectline_view.blade.php -->
