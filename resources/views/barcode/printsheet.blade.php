@extends('layouts.app')

@section('title', $langs->trans('BarCodePrintsheet'))

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">{{ $langs->trans('BarCodePrintsheet') }}</h1>
    
    <p class="opacity-70 mb-6">
        {{ $langs->trans('PageToGenerateBarCodeSheets', $langs->transnoentitiesnoconv('BuildPageToPrint')) }}
    </p>
    
    <form action="{{ route('barcode.printsheet') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="mode" value="label">
        <input type="hidden" name="action" value="builddoc">
        <input type="hidden" name="token" value="{{ currentToken() }}">
        
        {{-- Sheet Format Configuration --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">{{ $langs->trans('Configuration') }}</h2>
            
            <div class="space-y-4">
                {{-- Label Format --}}
                <div class="flex flex-col md:flex-row md:items-center gap-2">
                    <label class="md:w-1/3 font-medium">
                        {{ $langs->trans('DescADHERENT_ETIQUETTE_TYPE') }}
                    </label>
                    <div class="md:w-2/3">
                        {{ Form::selectarray('modellabel', $arrayoflabels, $modellabel, 1, 0, 0, '', 0, 0, 0, '', '', 1) }}
                    </div>
                </div>
                
                {{-- Number of Stickers --}}
                <div class="flex flex-col md:flex-row md:items-center gap-2">
                    <label class="md:w-1/3 font-medium">
                        {{ $langs->trans('NumberOfStickers') }}
                    </label>
                    <div class="md:w-2/3">
                        <input type="text" name="numberofsticker" size="4" 
                               value="{{ $numberofsticker }}" 
                               class="px-3 py-2 border rounded dark:bg-gray-700 dark:border-gray-600">
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Barcode Source Selection --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">{{ $langs->trans('BarcodeSource') }}</h2>
            
            <div class="space-y-3">
                {{-- Manual Entry --}}
                <div>
                    <input id="fillmanually" type="radio" name="selectorforbarcode" value="fillmanually"
                           class="radiobarcodeselect"
                           {{ (!$selectorforbarcode || $selectorforbarcode == 'fillmanually') ? 'checked' : '' }}>
                    <label for="fillmanually" class="ml-2">
                        {{ $langs->trans('FillBarCodeTypeAndValueManually') }}
                    </label>
                </div>
                
                {{-- From Product --}}
                @if($user->hasRight('produit', 'lire') || $user->hasRight('service', 'lire'))
                <div>
                    <input id="fillfromproduct" type="radio" name="selectorforbarcode" value="fillfromproduct"
                           class="radiobarcodeselect"
                           {{ $selectorforbarcode == 'fillfromproduct' ? 'checked' : '' }}>
                    <label for="fillfromproduct" class="ml-2">
                        {{ $langs->trans('FillBarCodeTypeAndValueFromProduct') }}
                    </label>
                    <div class="showforproductselector mt-2 ml-6">
                        {{ Form::select_produits($producttmp->id ?? 0, 'productid', '', 0, 0, -1, 2, '', 0, [], 0, '1', 0, 'minwidth400imp', 1) }}
                        <input type="submit" class="button small ml-2" id="submitproduct" name="submitproduct" 
                               value="{{ dol_escape_htmltag($langs->trans('GetBarCode')) }}">
                    </div>
                </div>
                @endif
                
                {{-- From Third Party --}}
                @if($user->hasRight('societe', 'lire'))
                <div>
                    <input id="fillfromthirdparty" type="radio" name="selectorforbarcode" value="fillfromthirdparty"
                           class="radiobarcodeselect"
                           {{ $selectorforbarcode == 'fillfromthirdparty' ? 'checked' : '' }}>
                    <label for="fillfromthirdparty" class="ml-2">
                        {{ $langs->trans('FillBarCodeTypeAndValueFromThirdParty') }}
                    </label>
                    <div class="showforthirdpartyselector mt-2 ml-6">
                        {{ Form::select_company($thirdpartytmp->id ?? 0, 'socid', '', 'SelectThirdParty', 0, 0, [], 0, 'minwidth300') }}
                        <input type="submit" class="button small ml-2" id="submitthirdparty" name="submitthirdparty"
                               value="{{ dol_escape_htmltag($langs->trans('GetBarCode')) }}">
                    </div>
                </div>
                @endif
            </div>
            
            {{-- Display loaded entity info --}}
            @if($producttmp->id ?? false)
            <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded">
                {{ $langs->trans('BarCodeDataForProduct', '') }} {!! $producttmp->getNomUrl(1) !!}
            </div>
            @endif
            
            @if($thirdpartytmp->id ?? false)
            <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded">
                {{ $langs->trans('BarCodeDataForThirdparty', '') }} {!! $thirdpartytmp->getNomUrl(1) !!}
            </div>
            @endif
        </div>
        
        {{-- Barcode Configuration --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">{{ $langs->trans('BarcodeConfiguration') }}</h2>
            
            <div class="space-y-4">
                {{-- Barcode Type --}}
                <div class="flex flex-col md:flex-row md:items-center gap-2">
                    <label class="md:w-1/3 font-medium">
                        {{ $langs->trans('BarcodeType') }}
                    </label>
                    <div class="md:w-2/3">
                        @php
                            require_once DOL_DOCUMENT_ROOT.'/Core/class/html.formbarcode.class.php';
                            $formbarcode = new FormBarCode($db);
                        @endphp
                        {!! $formbarcode->selectBarcodeType($fk_barcode_type, 'fk_barcode_type', 1) !!}
                    </div>
                </div>
                
                {{-- Barcode Value --}}
                <div class="flex flex-col md:flex-row md:items-center gap-2">
                    <label class="md:w-1/3 font-medium">
                        {{ $langs->trans('BarcodeValue') }}
                    </label>
                    <div class="md:w-2/3">
                        <input type="text" name="forbarcode" id="forbarcode" size="16" 
                               value="{{ $forbarcode }}" 
                               class="px-3 py-2 border rounded dark:bg-gray-700 dark:border-gray-600">
                    </div>
                </div>
                
                {{-- Product Reference and Label (only if product is selected) --}}
                @if($producttmp->id ?? false)
                <div class="flex flex-col md:flex-row md:items-center gap-2">
                    <div class="md:w-1/3">
                        <input id="label_product_ref_option" name="label_product_ref_option" type="checkbox"
                               {{ $label_product_ref_option ? 'checked' : '' }} class="checkforselect">
                        <label for="label_product_ref_option" class="ml-1">
                            {{ $langs->trans('BarcodeLabelProductRef') }}
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <input type="text" name="label_product_ref" id="label_product_ref" 
                               placeholder="{{ $langs->trans('BarcodeLabelProductRefPlaceholder') }}"
                               value="{{ $label_product_ref }}"
                               class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:border-gray-600">
                    </div>
                </div>
                
                <div class="flex flex-col md:flex-row md:items-center gap-2">
                    <div class="md:w-1/3">
                        <input id="label_product_label_option" name="label_product_label_option" type="checkbox"
                               {{ $label_product_label_option ? 'checked' : '' }} class="checkforselect">
                        <label for="label_product_label_option" class="ml-1">
                            {{ $langs->trans('BarcodeLabelProductLabel') }}
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <input type="text" name="label_product_label" id="label_product_label"
                               placeholder="{{ $langs->trans('BarcodeLabelProductLabelPlaceholder') }}"
                               value="{{ $label_product_label }}"
                               class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:border-gray-600">
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        {{-- Submit Button --}}
        <div>
            <button type="submit" class="button" id="submitformbarcodegen"
                    {{ !$selectorforbarcode ? 'disabled' : '' }}>
                {{ $langs->trans('BuildPageToPrint') }}
            </button>
        </div>
    </form>
</div>

{{-- JavaScript for dynamic form behavior --}}
<script type="text/javascript">
jQuery(document).ready(function() {
    function init_selectors() {
        if (jQuery("#fillmanually:checked").val() == "fillmanually") {
            jQuery("#submitproduct").prop("disabled", true);
            jQuery("#submitthirdparty").prop("disabled", true);
            jQuery("#search_productid").prop("disabled", true);
            jQuery("#socid").prop("disabled", true);
            jQuery(".showforproductselector").hide();
            jQuery(".showforthirdpartyselector").hide();
        }
        if (jQuery("#fillfromproduct:checked").val() == "fillfromproduct") {
            jQuery("#submitproduct").removeAttr("disabled");
            jQuery("#submitthirdparty").prop("disabled", true);
            jQuery("#search_productid").removeAttr("disabled");
            jQuery("#socid").prop("disabled", true);
            jQuery(".showforproductselector").show();
            jQuery(".showforthirdpartyselector").hide();
        }
        if (jQuery("#fillfromthirdparty:checked").val() == "fillfromthirdparty") {
            jQuery("#submitproduct").prop("disabled", true);
            jQuery("#submitthirdparty").removeAttr("disabled");
            jQuery("#search_productid").prop("disabled", true);
            jQuery("#socid").removeAttr("disabled");
            jQuery(".showforproductselector").hide();
            jQuery(".showforthirdpartyselector").show();
        }
    }
    init_selectors();
    jQuery(".radiobarcodeselect").click(function() {
        init_selectors();
    });

    function init_gendoc_button() {
        if (jQuery("#select_fk_barcode_type").val() > 0 && jQuery("#forbarcode").val()) {
            jQuery("#submitformbarcodegen").removeAttr("disabled");
        } else {
            jQuery("#submitformbarcodegen").prop("disabled", true);
        }
    }
    init_gendoc_button();
    jQuery("#select_fk_barcode_type").change(function() {
        init_gendoc_button();
    });
    jQuery("#forbarcode").keyup(function() {
        init_gendoc_button();
    });
});
</script>
@endsection
