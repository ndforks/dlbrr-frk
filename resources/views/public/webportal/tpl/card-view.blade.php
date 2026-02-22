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


$formconfirm = '';

// Call Hook formConfirm
$parameters = array('formConfirm' => $formconfirm);
$reshook = $hookmanager->executeHooks('formConfirm', $parameters, $context);
if (empty($reshook)) {
$formconfirm .= $hookmanager->resPrint;
} elseif ($reshook > 0) {
$formconfirm = $hookmanager->resPrint;
}
@endphp
<!-- file card-view.blade.php -->

{!! $formconfirm !!}

<article class="card-view-container" data-element-id="{{ dol_escape_htmltag((string) $formCard->object->id) }}" data-element="{{ dol_escape_htmltag($formCard->object->element) }}" >
{!! $this->loadTemplate('card-view-header') !!}

{!! $this->loadTemplate('card-view-properties') !!}

{!! $this->loadTemplate('card-view-lines') !!}

{!! $this->loadTemplate('card-view-footer') !!}
</article>
