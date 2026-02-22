{{-- Blade version of template
/* Copyright (C) 2010-2017  Laurent Destailleur     <eldy@users.sourceforge.net>
 * Copyright (C) 2018-2025  Frédéric France         <frederic.france@free.fr>
 * Copyright (C) 2024-2025	MDW						<mdeweerd@users.noreply.github.com>
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
 * $object must be defined
 * $backtopage
 */
--}}

<!-- BEGIN BLADE TEMPLATE PRODUCT/STOCK/TPL/STOCKCORRECTION.BLADE.PHP -->

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

$disableSellBy = getDolGlobalInt('PRODUCT_DISABLE_SELLBY');
$disableEatBy = getDolGlobalInt('PRODUCT_DISABLE_EATBY');
@endphp

@push('scripts')
<script type="text/javascript">
jQuery(document).ready(function() {
    function init_price() {
        if (jQuery("#mouvement").val() == '0') jQuery("#unitprice").removeAttr("disabled");
        else jQuery("#unitprice").prop("disabled", true);
    }
    init_price();
    jQuery("#mouvement").change(function() {
        console.log("We change the direction of movement");
        init_price();
    });
    jQuery("#nbpiece").keyup(function(event) {
        console.log("We enter a qty on "+event.key);
        if ( event.key == "-" ) {
            console.log("We set direction to value 1");
            jQuery("#nbpiece").val(jQuery("#nbpiece").val().replace("-", ""));
            jQuery("#mouvement option").removeAttr("selected").change();
            jQuery("#mouvement option[value=1]").attr("selected","selected").trigger("change");
            jQuery("#mouvement").trigger("change");
        } else if ( event.key == "+" ) {
            console.log("We set direction to value 0");
            jQuery("#nbpiece").val(jQuery("#nbpiece").val().replace("+", ""));
            jQuery("#mouvement option").removeAttr("selected").change();
            jQuery("#mouvement option[value=0]").attr("selected","selected").trigger("change");
            jQuery("#mouvement").trigger("change");
        }
    });
    @if ($disableSellBy == 0 || $disableEatBy == 0)
    var disableSellBy = {{ $disableSellBy }};
    var disableEatBy = {{ $disableEatBy }};
    jQuery("#batch_number").change(function(event) {
        var batch = jQuery(this).val();
        jQuery.getJSON("{{ DOL_URL_ROOT }}/product/ajax/product_lot.php?action=search&token={{ currentToken() }}&product_id={{ $id }}&batch="+batch, function(data) {
            if (data.length > 0) {
                var productLot = data[0];
                if (disableSellBy == 0) {
                    jQuery("#sellby").val(productLot.sellby);
                }
                if (disableEatBy == 0) {
                    jQuery("#eatby").val(productLot.eatby);
                }
            }
        });
    });
    @endif
});
</script>
@endpush

{!! load_fiche_titre($langs->trans("StockCorrection"), '', 'generic') !!}

<form action="{{ $_SERVER['PHP_SELF'] }}?id={{ $id }}" method="post">
    {!! dol_get_fiche_head(array(), '', '', 0, '', 0, '', '', 0, '', 0, 'marginbottomonly') !!}

    <input type="hidden" name="token" value="{{ newToken() }}">
    <input type="hidden" name="action" value="correct_stock">
    <input type="hidden" name="backtopage" value="{{ $backtopage }}">
    @if ($pdluoid)
        <input type="hidden" name="pdluoid" value="{{ $pdluoid }}">
    @endif

    <table class="border centpercent">
        <tr>
            @if ($object->element == 'product')
                <td class="fieldrequired">{{ $langs->trans("Warehouse") }}</td>
                <td>
                    @php
                        $ident = (GETPOST("dwid") ? GETPOSTINT("dwid") : (GETPOST('id_entrepot') ? GETPOSTINT('id_entrepot') : ($object->element == 'product' && $object->fk_default_warehouse ? $object->fk_default_warehouse : 'ifone')));
                        if (empty($ident) && getDolGlobalString('MAIN_DEFAULT_WAREHOUSE')) {
                            $ident = getDolGlobalString('MAIN_DEFAULT_WAREHOUSE');
                        }
                    @endphp
                    {!! img_picto('', 'stock', 'class="pictofixedwidth"') !!}{!! $formproduct->selectWarehouses($ident, 'id_entrepot', 'warehouseopen,warehouseinternal', 1, 0, 0, '', 0, 0, array(), 'minwidth100 maxwidth300 widthcentpercentminusx') !!}
                </td>
            @endif

            @if ($object->element == 'stockmouvement')
                <td class="fieldrequired">{{ $langs->trans("Product") }}</td>
                <td>
                    {!! img_picto('', 'product') !!}
                    {!! $form->select_produits(GETPOSTINT('product_id'), 'product_id', (!getDolGlobalString('STOCK_SUPPORTS_SERVICES') ? '0' : ''), 0, 0, -1, 2, '', 0, array(), 0, 1, 0, 'maxwidth500') !!}
                </td>
            @endif

            <td class="fieldrequired">{{ $langs->trans("NumberOfUnit") }}</td>
            <td>
                @if ($object->element == 'product' || $object->element == 'stockmouvement')
                    <select name="mouvement" id="mouvement" class="minwidth100 valignmiddle">
                        <option value="0">{{ $langs->trans("Add") }}</option>
                        <option value="1"{{ GETPOST('mouvement') ? ' selected="selected"' : '' }}>{{ $langs->trans("Delete") }}</option>
                    </select>
                    {!! ajax_combobox("mouvement") !!}
                @endif
                <input name="nbpiece" id="nbpiece" class="center valignmiddle maxwidth75" value="{{ GETPOST('nbpiece') }}">
            </td>
        </tr>

        @if (getDolGlobalString('PRODUIT_SOUSPRODUITS') && $object->element == 'product' && $object->hasFatherOrChild(1))
            <tr>
                <td></td>
                <td colspan="3">
                    <input type="checkbox" name="disablesubproductstockchange" id="disablesubproductstockchange" value="1"{{ GETPOST('disablesubproductstockchange') ? ' checked="checked"' : '' }}>
                    <label for="disablesubproductstockchange">{{ $langs->trans("DisableStockChangeOfSubProduct") }}</label>
                </td>
            </tr>
        @endif

        @if (isModEnabled('productbatch') && (($object->element == 'product' && $object->hasbatch()) || ($object->element == 'stockmouvement')))
            <tr>
                <td{{ $object->element == 'stockmouvement' ? '' : ' class="fieldrequired"' }}>{{ $langs->trans("batch_number") }}</td>
                <td colspan="3">
                    @if ($pdluoid > 0)
                        <input type="text" name="batch_number_bis" size="40" disabled="disabled" value="{{ GETPOST('batch_number') ? GETPOST('batch_number') : $pdluo->batch }}">
                        <input type="hidden" name="batch_number" value="{{ GETPOST('batch_number') ? GETPOST('batch_number') : $pdluo->batch }}">
                    @else
                        {!! img_picto('', 'barcode', 'class="pictofixedwidth"') !!}<input type="text" id="batch_number" name="batch_number" class="minwidth300" value="{{ GETPOST('batch_number') ? GETPOST('batch_number') : $pdluo->batch }}">
                    @endif
                </td>
            </tr>

            <tr>
                @if (!getDolGlobalString('PRODUCT_DISABLE_SELLBY'))
                    <td>{{ $langs->trans("SellByDate") }}</td>
                    <td>
                        @php
                            $sellbyselected = dol_mktime(0, 0, 0, GETPOSTINT('sellbymonth'), GETPOSTINT('sellbyday'), GETPOSTINT('sellbyyear'));
                        @endphp
                        {!! $form->selectDate(($pdluo->id > 0 ? $pdluo->sellby : $sellbyselected), 'sellby', 0, 0, 1, "", 1, 0, ($pdluoid > 0 ? 1 : 0)) !!}
                    </td>
                @endif
                @if (!getDolGlobalString('PRODUCT_DISABLE_EATBY'))
                    <td>{{ $langs->trans("EatByDate") }}</td>
                    <td>
                        @php
                            $eatbyselected = dol_mktime(0, 0, 0, GETPOSTINT('eatbymonth'), GETPOSTINT('eatbyday'), GETPOSTINT('eatbyyear'));
                        @endphp
                        {!! $form->selectDate(($pdluo->id > 0 ? $pdluo->eatby : $eatbyselected), 'eatby', 0, 0, 1, "", 1, 0, ($pdluoid > 0 ? 1 : 0)) !!}
                    </td>
                @endif
            </tr>
        @endif

        <tr>
            <td>{{ $langs->trans("UnitPurchaseValue") }}</td>
            <td colspan="{{ isModEnabled('project') ? '1' : '3' }}">
                <input name="unitprice" id="unitprice" size="10" value="{{ GETPOST('unitprice') }}">
            </td>
            @if (isModEnabled('project'))
                <td>{{ $langs->trans('Project') }}</td>
                <td>
                    {!! img_picto('', 'project') !!}
                    {!! $formproject->select_projects(-1, '', 'projectid', 0, 0, 1, 0, 0, 0, 0, '', 0, 0, 'maxwidth300 widthcentpercentminusx') !!}
                </td>
            @endif
        </tr>

        <tr>
            <td>{{ $langs->trans("MovementLabel") }}</td>
            <td>
                @php
                    $valformovementlabel = ((GETPOST("label") && (GETPOST('label') != $langs->trans("MovementCorrectStock", ''))) ? GETPOST("label") : $langs->trans("MovementCorrectStock", $productref));
                @endphp
                <input type="text" name="label" class="minwidth400" value="{{ dol_escape_htmltag($valformovementlabel) }}">
            </td>
            <td>{{ $langs->trans("InventoryCode") }}</td>
            <td>
                <input class="maxwidth100onsmartphone" name="inventorycode" id="inventorycode" value="{{ GETPOSTISSET('inventorycode') ? GETPOST('inventorycode', 'alpha') : dol_print_date(dol_now(), '%Y%m%d%H%M%S') }}">
            </td>
        </tr>
    </table>

    {!! dol_get_fiche_end() !!}

    <div class="center">
        <input type="submit" class="button button-save" name="save" value="{{ dol_escape_htmltag($langs->trans('Save')) }}">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="submit" class="button button-cancel" name="cancel" value="{{ dol_escape_htmltag($langs->trans('Cancel')) }}">
    </div>

    <br>
</form>

<!-- END BLADE STOCKCORRECTION.BLADE.PHP -->
