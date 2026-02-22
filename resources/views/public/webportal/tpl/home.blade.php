{{-- Blade template version --}}
@php
/* Copyright (C) 2024		MDW						<mdeweerd@users.noreply.github.com>
 * Copyright (C) 2025       Frédéric France         <frederic.france@free.fr>
 */
// Protection to avoid direct call of template
if (empty($context) || !is_object($context)) {
	print "Error, template page can't be called as URL";
	exit(1);
}
'@phan-var-force Context $context';
/**
 * @var Context $context
 * @var Translate $langs
 */
@endphp
<!-- file home.blade.php -->

<main class="container">
		<div class="home-links-grid grid">
			@if (isModEnabled('propal') && getDolGlobalInt('WEBPORTAL_PROPAL_LIST_ACCESS'))
			<article class="home-links-card --propal-list">
				<div class="home-links-card__icon" ></div>
				<a class="home-links-card__link" href="{{ $context->getControllerUrl('propallist') }}" title="{{ $langs->trans('WebPortalPropalListDesc') }}">{{ $langs->trans('WebPortalPropalListTitle') }}</a>
			</article>
			@endif
			@if (isModEnabled('order') && getDolGlobalInt('WEBPORTAL_ORDER_LIST_ACCESS'))
			<article class="home-links-card --order-list">
				<div class="home-links-card__icon" ></div>
				<a class="home-links-card__link" href="{{ $context->getControllerUrl('orderlist') }}" title="{{ $langs->trans('WebPortalOrderListDesc') }}">{{ $langs->trans('WebPortalOrderListTitle') }}</a>
			</article>
			@endif
			@if (isModEnabled('invoice') && getDolGlobalInt('WEBPORTAL_INVOICE_LIST_ACCESS'))
			<article class="home-links-card --invoice-list">
				<div class="home-links-card__icon" ></div>
				<a class="home-links-card__link" href="{{ $context->getControllerUrl('invoicelist') }}" title="{{ $langs->trans('WebPortalInvoiceListDesc') }}">{{ $langs->trans('WebPortalInvoiceListTitle') }}</a>
			</article>
			@endif
		</div>
</main>
