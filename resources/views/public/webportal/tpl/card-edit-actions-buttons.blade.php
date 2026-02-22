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
<!-- file card-edit-actions-buttons.blade.php -->

<div class="grid">
<div><input type="submit" name="save" role="button" value="{{ dolPrintHTMLForAttribute($langs->trans('Save')) }}" /></div>
<div><input type="submit" name="cancel" role="button" value="{{ dolPrintHTMLForAttribute($langs->trans('Cancel')) }}" /></div>
</div>
