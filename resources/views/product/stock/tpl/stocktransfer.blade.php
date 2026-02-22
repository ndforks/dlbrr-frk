{{-- Blade version of template
/* Copyright (C) 2010-2017  Laurent Destailleur     <eldy@users.sourceforge.net>
 * Copyright (C) 2018-2025  Frédéric France         <frederic.france@free.fr>
 * Copyright (C) 2024		MDW						<mdeweerd@users.noreply.github.com>
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
 */
--}}

<!-- BEGIN BLADE TEMPLATE PRODUCT/STOCK/TPL/STOCKTRANSFER.BLADE.PHP -->

@php
$productref = '';
if ($object->element == 'product') {
    $productref = (string) $object->ref;
}

$langs->load("productbatch");

$id = $id ?? $object->id;
$pdluoid = GETPOSTINT('pdluoid');
$pdluo = new Productbatch($db);

if ($pdluoid > 0) {
    $result = $pdluo->fetch($pdluoid);
    if ($result <= 0) {
        dol_print_error($db, $pdluo->error, $pdluo->errors);
    }
}
@endphp

{!! load_fiche_titre($langs->trans("StockTransfer"), '', 'generic') !!}

<form action="{{ $_SERVER['PHP_SELF'] }}?id={{ $id }}" method="post">
    {!! dol_get_fiche_head(array(), '', '', 0, '', 0, '', '', 0, '', 0, 'marginbottomonly') !!}

    <input type="hidden" name="token" value="{{ newToken() }}">
    <input type="hidden" name="action" value="transfert_stock">
    <input type="hidden" name="backtopage" value="{{ $backtopage }}">
    @if ($pdluoid)
        <input type="hidden" name="pdluoid" value="{{ $pdluoid }}">
    @endif

    <table class="border centpercent">
        <tr>
            @if ($object->element == 'product')
                <td class="fieldrequired">{{ $langs->trans("WarehouseSource") }}</td>
                <td>
                    {!! img_picto('', 'stock', 'class="pictofixedwidth"') !!}
                    @php
                        $selected = (GETPOST("dwid") ? GETPOSTINT("dwid") : (GETPOST('id_entrepot') ? GETPOSTINT('id_entrepot') : ($object->element == 'product' && $object->fk_default_warehouse ? $object->fk_default_warehouse : 'ifone')));
                        $warehousestatus = 'warehouseopen,warehouseinternal';
                    @endphp
                    {!! $formproduct->selectWarehouses($selected, 'id_entrepot', $warehousestatus, 1, 0, 0, '', 0, 0, array(), 'minwidth75 maxwidth300 widthcentpercentminusx') !!}
                </td>
            @endif

            @if ($object->element == 'stockmouvement')
                <td class="fieldrequired">{{ $langs->trans("Product") }}</td>
                <td>
                    {!! img_picto('', 'product', 'class="pictofixedwidth"') !!}
                    {!! $form->select_produits(GETPOSTINT('product_id'), 'product_id', (!getDolGlobalString('STOCK_SUPPORTS_SERVICES') ? '0' : ''), 0, 0, -1, 2, '', 0, array(), 0, 1, 0, 'maxwidth500') !!}
                </td>
            @endif

            <td class="fieldrequired">{{ $langs->trans("WarehouseTarget") }}</td>
            <td>
                {!! img_picto('', 'stock') !!}{!! $formproduct->selectWarehouses(GETPOST('id_entrepot_destination'), 'id_entrepot_destination', 'warehouseopen,warehouseinternal', 1, 0, 0, '', 0, 0, array(), 'minwidth75 maxwidth300 widthcentpercentminusx') !!}
            </td>
        </tr>
        <tr>
            <td class="fieldrequired">{{ $langs->trans("NumberOfUnit") }}</td>
            <td colspan="3">
                <input type="text" name="nbpiece" class="center maxwidth75" value="{{ dol_escape_htmltag(GETPOST('nbpiece')) }}">
            </td>
        </tr>

        @if (isModEnabled('productbatch') && (($object->element == 'product' && $object->hasbatch()) || ($object->element == 'stockmouvement')))
            <tr>
                <td{{ $object->element == 'stockmouvement' ? '' : ' class="fieldrequired"' }}>{{ $langs->trans("batch_number") }}</td>
                <td colspan="3">
                    @if ($pdluoid > 0)
                        <input type="text" name="batch_number_bis" size="40" disabled="disabled" value="{{ GETPOST('batch_number') ? GETPOST('batch_number') : $pdluo->batch }}">
                        <input type="hidden" name="batch_number" value="{{ GETPOST('batch_number') ? GETPOST('batch_number') : $pdluo->batch }}">
                    @else
                        {!! img_picto('', 'barcode', 'class="pictofixedwidth"') !!}<input type="text" name="batch_number" class="minwidth300 widthcentpercentminusx maxwidth300" value="{{ GETPOST('batch_number') ? GETPOST('batch_number') : $pdluo->batch }}">
                    @endif
                </td>
            </tr>

            <tr>
                @if (!getDolGlobalString('PRODUCT_DISABLE_SELLBY'))
                    <td>{{ $langs->trans("SellByDate") }}</td>
                    <td>
                        {!! $form->selectDate((!empty($d_sellby) ? $d_sellby : $pdluo->sellby), 'sellby', 0, 0, 1, "", 1, 0, ($pdluoid > 0 ? 1 : 0)) !!}
                    </td>
                @endif
                @if (!getDolGlobalString('PRODUCT_DISABLE_EATBY'))
                    <td>{{ $langs->trans("EatByDate") }}</td>
                    <td>
                        {!! $form->selectDate((!empty($d_eatby) ? $d_eatby : $pdluo->eatby), 'eatby', 0, 0, 1, "", 1, 0, ($pdluoid > 0 ? 1 : 0)) !!}
                    </td>
                @endif
            </tr>
        @endif

        <tr>
            <td>{{ $langs->trans("MovementLabel") }}</td>
            <td>
                @php $valformovementlabel = (GETPOST("label") ? GETPOST("label") : $langs->trans("MovementTransferStock", $productref)); @endphp
                <input type="text" name="label" class="minwidth300" value="{{ dol_escape_htmltag($valformovementlabel) }}">
            </td>
            <td>{{ $langs->trans("InventoryCode") }}</td>
            <td>
                <input class="maxwidth100onsmartphone" name="inventorycode" id="inventorycode" value="{{ GETPOSTISSET('inventorycode') ? GETPOST('inventorycode', 'alpha') : dol_print_date(dol_now(), '%Y%m%d%H%M%S') }}">
            </td>
        </tr>
    </table>

    {!! dol_get_fiche_end() !!}

    <div class="center">
        <input type="submit" class="button button-save" value="{{ dol_escape_htmltag($langs->trans('Save')) }}">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="submit" class="button button-cancel" name="cancel" value="{{ dol_escape_htmltag($langs->trans('Cancel')) }}">
    </div>

    <br>
</form>

<!-- END BLADE STOCKTRANSFER.BLADE.PHP -->
