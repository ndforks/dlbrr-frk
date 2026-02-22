{{-- Blade version of template --}}
@php
<!-- file hero-header-banner.blade.php -->
<section class="hero-header" {!! !empty($context->theme->bannerUseDarkTheme) ? ' data-theme="dark" ': '' !!} >
	<div class="container">
		<h1 class="hero-header__title">{!! $context->title !!}</h1>
		<div class="hero-header__desc">{!! $context->desc !!}</div>
	</div>
</section>
