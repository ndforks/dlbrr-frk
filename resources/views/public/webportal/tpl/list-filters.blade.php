<!-- file list-filters.blade.php -->
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
$formList = &$this->formList; !!}
<tr role="search-row">
	<td data-col="row-checkbox">
		<button class="btn-filter-icon btn-search-filters-icon" type="submit" name="button_search_x" value="x" aria-label="{!! dolPrintHTMLForAttribute((string) $langs->trans('Search')) ?>"></button>
		<button class="btn-filter-icon btn-remove-search-filters-icon" type="submit" name="button_removefilter_x" value="x" aria-label="{!! dolPrintHTMLForAttribute((string) $langs->trans('RemoveSearchFilters')) ?>"></button>
	</td>

	

foreach ($formList->object->fields as $key => $val) {
		$alias = $val['alias'] ?? 't.';
		if (array_key_exists($alias . $key, $formList->arrayfields) && !empty($formList->arrayfields[$alias . $key]['checked'])) {
			$cssforfield = $formList->getClasseCssList($key, $val);
			if ($key == 'status') $cssforfield .= ' parentonrightofpage'; !!}
	<td {!! empty($cssforfield) ? '' : 'class="' . dolPrintHTMLForAttribute($cssforfield) . '" ' ?>data-label="{!! dolPrintHTMLForAttribute((string) $formList->arrayfields[$alias . $key]['label']) ?>" data-col="{!! dolPrintHTMLForAttribute((string) $key) ?>">
			{!! $formList->printSearchInput($key, $val) !!}
	</td>
		

}
	}

	// Fields from hook
	$parameters = array();
	$reshook = $hookmanager->executeHooks('printFieldListOption', $parameters, $context);
	print $hookmanager->resPrint;

	// Remain to pay
	if (array_key_exists('remain_to_pay', $formList->arrayfields) && !empty($formList->arrayfields['remain_to_pay']['checked'])) { !!}
		<td data-label="{!! dolPrintHTMLForAttribute((string) $formList->arrayfields['remain_to_pay']['label']) ?>">
		</td>
	

}

	// Download link
	if (array_key_exists('download_link', $formList->arrayfields) && !empty($formList->arrayfields['download_link']['checked'])) { !!}
		<td data-label="{!! dolPrintHTMLForAttribute((string) $formList->arrayfields['download_link']['label']) ?>">
		</td>
	

}

	// Signature link
	if (array_key_exists('signature_link', $formList->arrayfields) && !empty($formList->arrayfields['signature_link']['checked'])) { !!}
		<td data-label="{!! dolPrintHTMLForAttribute((string) $formList->arrayfields['signature_link']['label']) ?>">
		</td>
	

} !!}
</tr>
