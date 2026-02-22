<!-- file list-nav.blade.php -->
@php
/* Copyright (C) 2025		Open-Dsi							<support@open-dsi.fr>
 */
// Protection to avoid direct call of template
if (empty($context) || !is_object($context)) {
	print "Error, template page can't be called as URL";
	exit(1);
}
'@phan-var-force Context $context';
'@phan-var-force AbstractListController $this';

/**
 * @var Conf					$conf
 * @var HookManager				$hookmanager
 * @var Translate				$langs
 * @var Context					$context
 * @var AbstractListController 	$this
 * @var FormListWebPortal 		$formList
 */
$formList = &$this->formList;

// Get nb pages
$nbPages = 0;
if ($formList->limit > 0) {
	$nbPages = ceil($formList->nbtotalofrecords / $formList->limit);
}
if ($nbPages <= 0) {
	$nbPages = 1;
}

$maxPaginationItem = min($nbPages, 5);
$minPageNum = max(1, $formList->page - 3);
$maxPageNum = min($nbPages, $formList->page + 3);

$params = $formList->params . '&amp;sortfield=' . $formList->sortfield . '&amp;sortorder=' . $formList->sortorder;
$params = preg_replace('/^(&|&amp;)/i', '', $params); // remove first & or &amp;
$url = $context->getControllerUrl($context->controller);
$url .= (preg_match('/\?/', $url) ? '&amp;' : '?') . $params; !!}
<input type="hidden" name="page" value="{!! dolPrintHTMLForAttribute((string) $formList->page) ?>">
<nav id="webportal-{!! dolPrintHTMLForAttribute($formList->object->element) ?>-pagination">
	<ul>
		<li><strong>{!! $langs->trans($formList->titleKey) ?></strong> ({!! $formList->nbtotalofrecords ?>)</li>
	</ul>

	

if ($nbPages > 1) { !!}
	<ul class="pages-nav-list">
		

if ($formList->page > 1) { !!}
		<li><a class="pages-nav-list__icon --prev" aria-label="{!! dolPrintHTMLForAttribute((string) $langs->trans('AriaPrevPage')) ?>" href="{!! $url . '&amp;page=' . ($formList->page - 1) ?>"

// print ($formList->page <= 1 ? ' disabled' : '') ?>></a></li>
		

} !!}
		

if ($minPageNum > 1) { !!}
			<li><a class="pages-nav-list__link {!! ($formList->page == 1 ? '--active' : '') ?>" aria-label="{!! dolPrintHTMLForAttribute((string) $langs->trans('AriaPageX', 1)) ?>" href="{!! $url . '&amp;page=1' ?>">1</a></li>
			<li>&hellip;</li>
		

} !!}
		

for ($p = $minPageNum; $p <= $maxPageNum; $p++) { !!}
			<li><a class="pages-nav-list__link {!! ($formList->page === $p ? '--active' : '') ?>" aria-label="{!! dolPrintHTMLForAttribute((string) $langs->trans('AriaPageX', $p)) ?>"  href="{!! $url . '&amp;page=' . $p ?>">{!! $p ?></a></li>
		

} !!}
		

if ($maxPaginationItem < $nbPages) { !!}
			<li>&hellip;</li>
			<li><a class="pages-nav-list__link {!! ($formList->page == $nbPages ? '--active' : '') ?>" aria-label="{!! dolPrintHTMLForAttribute((string) $langs->trans('AriaPageX', $nbPages)) ?>" href="{!! $url . '&amp;page=' . $nbPages ?>">{!! $nbPages ?></a></li>
		

} !!}
		

if ($formList->page < $nbPages) { !!}
			<li><a class="pages-nav-list__icon --next" aria-label="{!! dolPrintHTMLForAttribute((string) $langs->trans('AriaNextPage')) ?>" href="{!! $url . '&amp;page=' . ($formList->page + 1) ?>"

// print ($formList->page >= $nbPages ? ' disabled' : '') ?>></a></li>
		

} !!}
	</ul>
	

} !!}
</nav>
