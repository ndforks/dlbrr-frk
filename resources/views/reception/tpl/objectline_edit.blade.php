{{-- Blade template version
/* Copyright (C) 2010-2012	Regis Houssin		        <regis.houssin@inodbox.com>
 * Copyright (C) 2010-2012	Laurent Destailleur	    <eldy@users.sourceforge.net>
 * Copyright (C) 2012		    Christophe Battarel	    <christophe.battarel@altairis.fr>
 * Copyright (C) 2012       Cédric Salvador         <csalvador@gpcsolutions.fr>
 * Copyright (C) 2012-2014  Raphaël Doursenaud      <rdoursenaud@gpcsolutions.fr>
 * Copyright (C) 2013		    Florian Henry		        <florian.henry@open-concept.pro>
 * Copyright (C) 2018-2024  Frédéric France         <frederic.france@free.fr>
 * Copyright (C) 2024		    Vincent Maury		        <vmaury@timgroup.fr>
 * Copyright (C) 2024		    MDW						          <mdeweerd@users.noreply.github.com>
 * Copyright (C) 2025		    Nick Fragoulis
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
$forceall = $forceall ?? 0;
$filtertype = $filtertype ?? 0;
$colspan = 3;
$coldisplay = 0;
$tmpproduct = null;
if ($line->fk_product > 0) {
    $tmpproduct = new Product($object->db);
    $tmpproduct->fetch($line->fk_product);
}
$unit_type = false;
if (getDolGlobalString('PRODUCT_USE_UNITS') && !empty($line->fk_unit) && !getDolGlobalString('MAIN_EDIT_LINE_ALLOW_ALL_UNIT_TYPE')) {
    $cUnit = new CUnits($line->db);
    if ($cUnit->fetch((int) $line->fk_unit) > 0 && !empty($cUnit->unit_type)) {
        $unit_type = $cUnit->unit_type;
    }
}
@endphp

<!-- BEGIN BLADE TEMPLATE reception/tpl/objectline_edit.blade.php -->

<tr class="oddeven tredited">
    @if (getDolGlobalString('MAIN_VIEW_LINE_NUMBER'))
        @php $coldisplay++; @endphp
        <td class="linecolnum center">{{ $i + 1 }}</td>
    @endif

    @php $coldisplay++; @endphp
    <td>
        <div id="line_{{ $line->id }}"></div>

        <input type="hidden" name="lineid" value="{{ $line->id }}">
        <input type="hidden" id="product_type" name="type" value="{{ $line->product_type }}">
        <input type="hidden" id="product_id" name="productid" value="{{ !empty($line->fk_product) ? $line->fk_product : 0 }}" />
        <input type="hidden" id="special_code" name="special_code" value="{{ $line->special_code }}">

        @if ($tmpproduct)
            {!! $tmpproduct->getNomUrl(1) !!} - {{ $tmpproduct->label }}
        @endif

        @if (!empty($extrafields))
            @php
                $temps = $line->showOptionals($extrafields, 'edit', array('class' => 'tredited'), '', '', '1', 'line');
            @endphp
            @if (!empty($temps))
                <div style="padding-top: 10px" id="extrafield_lines_area_edit" name="extrafield_lines_area_edit">
                    {!! $temps !!}
                </div>
            @endif
        @endif
    </td>

    @php $coldisplay++; @endphp
    <td class="nobottom linecolqty right">
        @if (((int) $line->info_bits & 2) != 2)
            <input size="3" type="text" class="flat right" name="qty" id="qty" value="{{ $line->qty }}">
        @endif
    </td>

    @if (getDolGlobalString('PRODUCT_USE_UNITS'))
        @php $coldisplay++; @endphp
        <td class="left">
            {!! $form->selectUnits(GETPOSTISSET('units') ? GETPOST('units') : $line->fk_unit, "units", 0, $unit_type) !!}
        </td>
    @endif

    @php $coldisplay += $colspan * 2; @endphp
    <td class="nobottom linecoledit center valignmiddle" colspan="{{ $colspan }}">
        <input type="submit" class="reposition button buttongen margintoponly marginbottomonly button-save" id="savelinebutton" name="save" value="{{ $langs->trans('Save') }}">
        <input type="submit" class="reposition button buttongen margintoponly marginbottomonly button-cancel" id="cancellinebutton" name="cancel" value="{{ $langs->trans('Cancel') }}">
    </td>
</tr>

<!-- END BLADE TEMPLATE objectline_edit.blade.php -->
