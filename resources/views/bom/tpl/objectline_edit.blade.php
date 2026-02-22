{{--
/* Copyright (C) 2010-2012Regis Houssin    <regis.houssin@inodbox.com>
 * Copyright (C) 2010-2012Laurent Destailleur    <eldy@users.sourceforge.net>
 * Copyright (C) 2012Christophe Battarel    <christophe.battarel@altairis.fr>
 * Copyright (C) 2012       Cédric Salvador         <csalvador@gpcsolutions.fr>
 * Copyright (C) 2012-2014  Raphaël Doursenaud      <rdoursenaud@gpcsolutions.fr>
 * Copyright (C) 2013Florian Henry    <florian.henry@open-concept.pro>
 * Copyright (C) 2018-2025  Frédéric France         <frederic.france@free.fr>
 * Copyright (C) 2024Vincent Maury    <vmaury@timgroup.fr>
 * Copyright (C) 2024-2025MDW<mdeweerd@users.noreply.github.com>
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
 * $seller, $buyer
 * $dateSelector
 * $forceall (0 by default, 1 for supplier invoices/orders)
 * $senderissupplier (0 by default, 1 for supplier invoices/orders)
 * $inputalsopricewithtax (0 by default, 1 to also show column with unit price including tax)
 */
--}}

@php
require_once DOL_DOCUMENT_ROOT."/product/class/html.formproduct.class.php";

global $forceall, $filtertype;

if (empty($forceall)) {
$forceall = 0;
}

if (empty($filtertype)) {
$filtertype = 0;
}

$formproduct = new FormProduct($object->db);

// Define colspan for the button 'Add'
$colspan = 3; // Columns: total ht + col edit + col delete

// Lines for extrafield
$objectline = new BOMLine($this->db);

$coldisplay = 0;
@endphp

<!-- BEGIN BLADE TEMPLATE bom/tpl/objectline_edit -->
<tr class="oddeven tredited bg-blue-50 dark:bg-blue-900/20">
@if(getDolGlobalString('MAIN_VIEW_LINE_NUMBER'))
@php $coldisplay++; @endphp
<td class="linecolnum center">{{ $i + 1 }}</td>
@endif

@php
$coldisplay++;
$tmpproduct = new Product($object->db);
if ($line->fk_product > 0) {
$tmpproduct->fetch($line->fk_product);
}
@endphp

<td>
<div id="line_{{ $line->id }}"></div>

<input type="hidden" name="lineid" value="{{ $line->id }}">
<input type="hidden" id="product_type" name="type" value="{{ $line->product_type }}">
<input type="hidden" id="product_id" name="productid" value="{{ !empty($line->fk_product) ? $line->fk_product : 0 }}" />
<input type="hidden" id="special_code" name="special_code" value="{{ $line->special_code }}">

@if($line->fk_product > 0)
{!! $tmpproduct->getNomUrl(1) !!}
@endif

@if(is_object($hookmanager ?? null))
@php
$parameters = array('line' => $line, 'var' => $var, 'seller' => $seller, 'buyer' => $buyer);
$reshook = $hookmanager->executeHooks('formEditProductOptions', $parameters, $this, $action);
@endphp
@endif

@if(is_object($objectline) && !empty($extrafields))
@php
$temps = $line->showOptionals($extrafields, 'edit', array('class' => 'tredited'), '', '', '1', 'line');
@endphp
@if(!empty($temps))
<div style="padding-top: 10px" id="extrafield_lines_area_edit" name="extrafield_lines_area_edit">
{!! $temps !!}
</div>
@endif
@endif
</td>

@php $coldisplay++; @endphp
<td class="nobottom linecolqty right">
@if(((int) $line->info_bits & 2) != 2)
<input size="3" type="text" class="flat right w-16 px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" name="qty" id="qty" value="{{ $line->qty }}">
@endif
</td>

@if($filtertype != 1)
@if(getDolGlobalInt('PRODUCT_USE_UNITS'))
@php $coldisplay++; @endphp
<td class="nobottom nowrap linecolunit">
{!! $formproduct->selectMeasuringUnits("fk_unit", '', ($line->fk_unit) ? $line->fk_unit : '', 0, 0) !!}
</td>
@endif
@else
@php $coldisplay++; @endphp
<td class="nobottom nowrap linecolunit">
{!! $formproduct->selectMeasuringUnits("fk_unit", "time", ($line->fk_unit) ? $line->fk_unit : '', 0, 0) !!}
</td>
@endif

@if($filtertype != 1 || getDolGlobalString('STOCK_SUPPORTS_SERVICES'))
@php $coldisplay++; @endphp
<td class="nobottom linecolqtyfrozen right">
<input type="checkbox" name="qty_frozen" id="qty_frozen" class="flat right" value="1"{{ GETPOSTISSET("qty_frozen") ? (GETPOSTINT('qty_frozen') ? ' checked="checked"' : '') : ($line->qty_frozen ? ' checked="checked"' : '') }}>
</td>

@php $coldisplay++; @endphp
<td class="nobottom linecoldisablestockchange right">
<input type="checkbox" name="disable_stock_change" id="disable_stock_change" class="flat right" value="1"{{ GETPOSTISSET('disablestockchange') ? (GETPOSTINT("disable_stock_change") ? ' checked="checked"' : '') : ($line->disable_stock_change ? ' checked="checked"' : '') }}>
</td>

@php $coldisplay++; @endphp
<td class="nobottom nowrap linecollost right">
<input type="text" size="2" name="efficiency" id="efficiency" class="flat right w-16 px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $line->efficiency }}">
</td>
@endif

@if($filtertype == 1 && isModEnabled('workstation'))
@php $coldisplay++; @endphp
<td class="nobottom nowrap linecolworkstation">
{!! $formproduct->selectWorkstations($line->fk_default_workstation, 'idworkstations', 1) !!}
</td>
@endif

@php $coldisplay++; @endphp
<td class="nobottom nowrap linecolcostprice right">
</td>

@php $coldisplay += $colspan; @endphp
<td class="nobottom linecoledit center valignmiddle" colspan="{{ $colspan }}">
<input type="submit" class="reposition button buttongen margintoponly marginbottomonly button-save bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded cursor-pointer transition-colors" id="savelinebutton" name="save" value="{{ $langs->trans("Save") }}">
<input type="submit" class="reposition button buttongen margintoponly marginbottomonly button-cancel bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded cursor-pointer transition-colors" id="cancellinebutton" name="cancel" value="{{ $langs->trans("Cancel") }}">
</td>
</tr>

<!-- END BLADE TEMPLATE objectline_edit -->
