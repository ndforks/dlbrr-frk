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
@endphp
<!-- file list.blade.php -->

<form method="POST" id="searchFormList" action="{{ $context->getControllerUrl($context->controller, '', false) }}">
{!! $context->getFormToken() !!}
<input type="hidden" name="formfilteraction" id="formfilteraction" value="list">
<input type="hidden" name="action" value="list">
<input type="hidden" name="sortfield" value="{{ dolPrintHTMLForAttribute($formList->sortfield) }}">
<input type="hidden" name="sortorder" value="{{ dolPrintHTMLForAttribute($formList->sortorder) }}">
<input type="hidden" name="contextpage" value="{{ dolPrintHTMLForAttribute($formList->contextpage) }}">

{!! $this->loadTemplate('list-nav') !!}

{!! $this->loadTemplate('list-additional-filters') !!}

<table id="webportal-{{ dolPrintHTMLForAttribute($formList->object->element) }}-list" responsive="scroll" role="grid">
<thead>
{!! $this->loadTemplate('list-filters') !!}

{!! $this->loadTemplate('list-titles') !!}
</thead>

<tbody>
{!! $this->loadTemplate('list-lines') !!}
</tbody>

<tfoot>
{!! $this->loadTemplate('list-footer') !!}
</tfoot>
</table>
</form>
