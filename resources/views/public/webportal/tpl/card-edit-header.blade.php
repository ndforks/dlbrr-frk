{{-- Blade template version --}}
@php
/* Copyright (C) 2025		Open-Dsi							<support@open-dsi.fr>
 */
// Protection to avoid direct call of template
if (empty($context) || !is_object($context)) {
	print "Error, template page can't be called as URL";
	exit(1);
}
'@phan-var-force Context $context';
'@phan-var-force AbstractCardController $this';

/**
 * @var Conf					$conf
 * @var HookManager				$hookmanager
 * @var Translate				$langs
 * @var Context					$context
 * @var AbstractCardController 	$this
 * @var FormCardWebPortal 		$formCard
 */
$formCard = $this->formCard;
@endphp
<!-- file card-edit-header.blade.php -->

<header>
	<div>
		<div class="header-card-main-information inline-block valignmiddle">
			<h2>{{ $langs->trans($formCard->titleKey) }}</h2>
		</div>
	</div>
</header>
