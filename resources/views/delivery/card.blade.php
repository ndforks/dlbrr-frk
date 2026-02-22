@extends('layouts.app')

@section('title', __('DeliveryReceipt'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-semibold mb-6">{{ __('DeliveryReceipt') }}</h1>

    @if($object->id > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2">{{ $object->ref }}</h2>
            @if($expedition)
            <p class="text-gray-600 dark:text-gray-400">
                {{ __('RefCustomer') }}: {{ $expedition->ref_customer }}
            </p>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium mb-2">{{ __('Details') }}</h3>
                <dl class="space-y-2">
                    <div>
                        <dt class="font-semibold">{{ __('Status') }}:</dt>
                        <dd>{!! $object->getLibStatut(5) !!}</dd>
                    </div>
                    @if($object->date_delivery)
                    <div>
                        <dt class="font-semibold">{{ __('DateDelivery') }}:</dt>
                        <dd>{{ dol_print_date($object->date_delivery, 'day') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            @if($objectsrc)
            <div>
                <h3 class="text-lg font-medium mb-2">{{ __('Origin') }}</h3>
                <dl class="space-y-2">
                    <div>
                        <dt class="font-semibold">{{ __('Order') }}:</dt>
                        <dd>{{ $objectsrc->ref }}</dd>
                    </div>
                </dl>
            </div>
            @endif
        </div>

        @if($action === 'delete')
        <div class="mt-6 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg p-4">
            <p class="text-red-800 dark:text-red-200 mb-4">
                {{ __('DeleteDeliveryReceiptConfirm', $object->ref) }}
            </p>
            <form method="POST" action="{{ route('delivery.card', ['id' => $object->id]) }}">
                @csrf
                <input type="hidden" name="action" value="confirm_delete">
                <input type="hidden" name="confirm" value="yes">
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">
                    {{ __('Delete') }}
                </button>
                <a href="{{ route('delivery.card', ['id' => $object->id]) }}" class="ml-2 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded">
                    {{ __('Cancel') }}
                </a>
            </form>
        </div>
        @endif
    </div>
    @else
    <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
        <p class="text-yellow-800 dark:text-yellow-200">
            {{ __('NoDeliveryReceiptFound') }}
        </p>
    </div>
    @endif
</div>
@endsection
