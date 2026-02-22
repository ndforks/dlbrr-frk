@php
/* Copyright (C) 2025Open-Dsi<support@open-dsi.fr>
 */
// Protection to avoid direct call of template
if (empty($context) || !is_object($context)) {
print "Error, template page can't be called as URL";
exit(1);
}
'@phan-var-force Context $context';
'@phan-var-force AbstractCardController $this';

/**
 * @var Conf$conf
 * @var HookManager$hookmanager
 * @var Translate$langs
 * @var Context$context
 * @var AbstractCardController $this
 * @var FormCardWebPortal $formCard
 */
$formCard = $this->formCard;
@endphp
<!-- file card-edit.blade.php -->

<form method="POST" action="{{ $context->getControllerUrl($context->controller, [ 'id' => $formCard->object->id ], false) }}">
{!! $context->getFormToken() !!}
<input type="hidden" name="action" value="update">
@if ($formCard->backtopage)
<input type="hidden" name="backtopage" value="{{ dolPrintHTMLForAttribute($formCard->backtopage) }}">
@endif
@if ($formCard->backtopageforcancel)
<input type="hidden" name="backtopageforcancel" value="{{ dolPrintHTMLForAttribute($formCard->backtopageforcancel) }}">
@endif

<article>
{!! $this->loadTemplate('card-edit-header') !!}

{!! $this->loadTemplate('card-edit-properties') !!}

{!! $this->loadTemplate('card-edit-lines') !!}


{!! $this->loadTemplate('card-edit-footer') !!}
</article>

</form>
