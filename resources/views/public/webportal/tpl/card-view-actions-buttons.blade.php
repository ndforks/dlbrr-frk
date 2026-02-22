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

$url = $context->getControllerUrl($context->controller). '&id=' . $formCard->object->id;
@endphp
<!-- file card-view-actions-buttons.blade.php -->

@if ($formCard->action != 'presend' && $formCard->action != 'editline')
<div id="actions_buttons">
@php
$parameters = array();
$reshook = $hookmanager->executeHooks('addMoreActionsButtons', $parameters, $context);
if ($reshook < 0) {
$context->setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');
} elseif (empty($reshook)) {
// Edit card
if ($formCard->permissiontoadd) {
@endphp
<a href="{{ $url . '&action=edit' }}" role="button">{{ $langs->trans('Modify') }}</a>
@php
}
}
@endphp
</div>
@endif
