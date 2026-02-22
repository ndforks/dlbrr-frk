@extends('layouts.app')

@section('title', $langs->trans('MassBarcodeInit'))

@section('content')
@php
global $langs, $conf;

$productModuleEnabled = isModEnabled('product') || isModEnabled('service');
$thirdpartyModuleEnabled = isModEnabled('societe');
@endphp

<div class="container mx-auto px-4 py-6">
    @if(!request()->input('dol_openinpopup'))
        <h1 class="text-2xl font-bold mb-4">{{ $langs->trans('MassBarcodeInit') }}</h1>
    @endif

    <p class="text-gray-600 dark:text-gray-400 mb-6">
        {{ $langs->trans('MassBarcodeInitDesc') }}
    </p>

    <script type="text/javascript">
    function confirm_erase() {
        return confirm("{{ dol_escape_js($langs->trans('ConfirmEraseAllCurrentBarCode')) }}");
    }
    </script>

    {{-- Products/Services Section --}}
    @if($productModuleEnabled)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4 flex items-center">
                <i class="fas fa-box mr-2"></i>
                {{ $langs->trans('BarcodeInitForProductsOrServices') }}
            </h2>

            <div class="mb-4">
                <p class="text-gray-700 dark:text-gray-300">
                    {{ $langs->trans('CurrentlyNWithoutBarCode', $productStats['nbWithoutBarcode'], $productStats['nbTotal'], $langs->transnoentitiesnoconv('ProductsOrServices')) }}
                </p>
            </div>

            @if($modBarCodeProduct)
                <div class="mb-4">
                    <p class="text-gray-700 dark:text-gray-300">
                        <strong>{{ $langs->trans('BarCodeNumberManager') }}:</strong>
                        <span class="font-medium">
                            {{ $modBarCodeProduct->name ?? $modBarCodeProduct->nom }}
                        </span>
                        -
                        <strong>{{ $langs->trans('NextValue') }}:</strong>
                        <span class="font-medium">
                            @php
                            $objproduct = new Product($GLOBALS['db']);
                            echo $modBarCodeProduct->getNextValue($objproduct);
                            @endphp
                        </span>
                    </p>
                </div>

                <form action="{{ route('barcode.codeinit') }}" method="POST" class="flex items-center gap-3">
                    @csrf
                    <input type="hidden" name="action" value="initbarcodeproducts">
                    <input type="hidden" name="dol_openinpopup" value="{{ $dol_openinpopup }}">

                    <button 
                        type="submit" 
                        name="submitformbarcodeproductgen"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                        @if($productStats['nbWithoutBarcode'] == 0) disabled @endif
                    >
                        {{ $langs->trans('InitEmptyBarCode', min($maxperinit, $productStats['nbWithoutBarcode'])) }}
                    </button>

                    <button 
                        type="submit" 
                        name="eraseallproductbarcode"
                        value="1"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                        onclick="return confirm_erase();"
                        @if($productStats['nbWithoutBarcode'] == $productStats['nbTotal']) disabled @endif
                    >
                        {{ $langs->trans('EraseAllCurrentBarCode') }}
                    </button>
                </form>
            @else
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded p-4">
                    <p class="text-yellow-800 dark:text-yellow-200 mb-2">
                        {{ $langs->trans('NoBarcodeNumberingTemplateDefined') }}
                    </p>
                    <a 
                        href="{{ DOL_URL_ROOT }}/admin/barcode.php" 
                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 underline"
                    >
                        {{ $langs->trans('ToGenerateCodeDefineAutomaticRuleFirst') }}
                    </a>
                </div>
            @endif
        </div>
    @endif

    {{-- Third Parties Section --}}
    @if($thirdpartyModuleEnabled)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4 flex items-center">
                <i class="fas fa-building mr-2"></i>
                {{ $langs->trans('BarcodeInitForThirdparties') }}
            </h2>

            <div class="mb-4">
                <p class="text-gray-700 dark:text-gray-300">
                    {{ $langs->trans('CurrentlyNWithoutBarCode', $thirdpartyStats['nbWithoutBarcode'], $thirdpartyStats['nbTotal'], $langs->transnoentitiesnoconv('ThirdParties')) }}
                </p>
            </div>

            @if($modBarCodeThirdparty)
                <div class="mb-4">
                    <p class="text-gray-700 dark:text-gray-300">
                        <strong>{{ $langs->trans('BarCodeNumberManager') }}:</strong>
                        <span class="font-medium">
                            {{ $modBarCodeThirdparty->name ?? $modBarCodeThirdparty->nom }}
                        </span>
                        -
                        <strong>{{ $langs->trans('NextValue') }}:</strong>
                        <span class="font-medium">
                            @php
                            $objthirdparty = new Societe($GLOBALS['db']);
                            echo $modBarCodeThirdparty->getNextValue($objthirdparty);
                            @endphp
                        </span>
                    </p>
                </div>

                <form action="{{ route('barcode.codeinit') }}" method="POST" class="flex items-center gap-3">
                    @csrf
                    <input type="hidden" name="action" value="initbarcodethirdparties">
                    <input type="hidden" name="dol_openinpopup" value="{{ $dol_openinpopup }}">

                    <button 
                        type="submit" 
                        name="submitformbarcodethirdpartygen"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                        @if($thirdpartyStats['nbWithoutBarcode'] == 0) disabled @endif
                    >
                        {{ $langs->trans('InitEmptyBarCode', $thirdpartyStats['nbWithoutBarcode']) }}
                    </button>

                    <button 
                        type="submit" 
                        name="eraseallthirdpartybarcode"
                        value="1"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                        onclick="return confirm_erase();"
                        @if($thirdpartyStats['nbWithoutBarcode'] == $thirdpartyStats['nbTotal']) disabled @endif
                    >
                        {{ $langs->trans('EraseAllCurrentBarCode') }}
                    </button>
                </form>
            @else
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded p-4">
                    <p class="text-yellow-800 dark:text-yellow-200 mb-2">
                        {{ $langs->trans('NoBarcodeNumberingTemplateDefined') }}
                    </p>
                    <a 
                        href="{{ DOL_URL_ROOT }}/admin/barcode.php" 
                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 underline"
                    >
                        {{ $langs->trans('ToGenerateCodeDefineAutomaticRuleFirst') }}
                    </a>
                </div>
            @endif
        </div>
    @endif

    {{-- Print Sheet Section --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">
            {{ $langs->trans('BarCodePrintsheet') }}
        </h2>

        <p class="text-gray-700 dark:text-gray-300">
            {{ $langs->trans('ClickHereToGoTo') }}:
            <a 
                href="{{ route('barcode.printsheet') }}" 
                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 underline"
            >
                {{ $langs->trans('BarCodePrintsheet') }}
            </a>
        </p>
    </div>
</div>
@endsection
