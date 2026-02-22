@php
/* Copyright (C) 2025Open-Dsi<support@open-dsi.fr>
 */
// Protection to avoid direct call of template
if (empty($context) || !is_object($context)) {
print "Error, template page can't be called as URL";
exit(1);
}
'@phan-var-force Context $context';
'@phan-var-force AbstractListController $this';

/**
 * @var Conf$conf
 * @var HookManager$hookmanager
 * @var Translate$langs
 * @var Context$context
 * @var AbstractListController $this
 * @var FormListWebPortal $formList
 */
$formList = &$this->formList;

// Filters
$moreforfilter = '';

$parameters = array();
$reshook = $hookmanager->executeHooks('printFieldPreListTitle', $parameters, $context);
if (empty($reshook)) {
$moreforfilter .= $hookmanager->resPrint;
} else {
$moreforfilter = $hookmanager->resPrint;
}
@endphp
<!-- file list-additional-filters.blade.php -->

@if (!empty($moreforfilter))
<div id="webportal-{{ dolPrintHTMLForAttribute($formList->object->element) }}-additional-filters" class="centpercent">
{!! $moreforfilter !!}
</div>
@endif
