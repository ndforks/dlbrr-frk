<!-- file list-lines.blade.php -->
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

if (!empty($formList->records)) {
	$totalarray = [
		'nbfield' => 0,
		'totalizable' => [],
	];

	foreach ($formList->records as $i => $record) {
		// Store properties in $object
		$formList->setVarsFromFetchObj($record); !!}
		<tr data-rowid="{!! dolPrintHTMLForAttribute((string) $formList->object->id) ?>">
			<td></td>
			

$formList->setTotalValue('', [], $record, $i, $totalarray) !!}
			

foreach ($formList->object->fields as $key => $val) {
				$alias = $val['alias'] ?? 't.';
				if (array_key_exists($alias . $key, $formList->arrayfields) && !empty($formList->arrayfields[$alias . $key]['checked'])) {
					$cssforfield = $formList->getClasseCssList($key, $val, true);
					if (preg_match('/tdoverflow/', $cssforfield)) $cssforfield .= ' classfortooltip';
					$title = '';
					if (preg_match('/tdoverflow/', $cssforfield) && !is_numeric($formList->object->$key)) {
						$title = ' title="' . dolPrintHTMLForAttribute((string) $formList->object->$key) . '"';
					} !!}
					<td {!! (empty($cssforfield) ? '' : 'class="' . dolPrintHTMLForAttribute($cssforfield) . '" '); print $title ?>data-label="{!! dolPrintHTMLForAttribute((string) $formList->arrayfields[$alias . $key]['label']) ?>" data-col="{!! dolPrintHTMLForAttribute((string) $key) ?>">
						{!! $formList->printValue($key, $val, $record, $i, $totalarray);
						$formList->setTotalValue($key, $val, $record, $i, $totalarray) !!}
					</td>
				

}
			}

			// Fields from hook
			$parameters = array('record' => $record, 'i' => $i, 'totalarray' => &$totalarray);
			$reshook = $hookmanager->executeHooks('printFieldListValue', $parameters, $context);
			print $hookmanager->resPrint;

			// Remain to pay
			if (array_key_exists('remain_to_pay', $formList->arrayfields) && !empty($formList->arrayfields['remain_to_pay']['checked'])) { !!}
				<td class="nowraponall" data-label="{!! dolPrintHTMLForAttribute((string) $formList->arrayfields['remain_to_pay']['label']) ?>" data-col="remain_to_pay">
					{!! $formList->printValue('remain_to_pay', [], $record, $i, $totalarray);
					$formList->setTotalValue('remain_to_pay', [], $record, $i, $totalarray) !!}
				</td>
			

}

			// Download link
			if (array_key_exists('download_link', $formList->arrayfields) && !empty($formList->arrayfields['download_link']['checked'])) { !!}
				<td data-label="{!! dolPrintHTMLForAttribute((string) $formList->arrayfields['download_link']['label']) ?>" data-col="download_link">
					{!! $formList->printValue('download_link', [], $record, $i, $totalarray);
					$formList->setTotalValue('download_link', [], $record, $i, $totalarray) !!}
				</td>
			

}

			// Signature link
			if (array_key_exists('signature_link', $formList->arrayfields) && !empty($formList->arrayfields['signature_link']['checked'])) { !!}
				<td data-label="{!! dolPrintHTMLForAttribute((string) $formList->arrayfields['signature_link']['label']) ?>" data-col="signature_link">
					{!! $formList->printValue('signature_link', [], $record, $i, $totalarray);
					$formList->setTotalValue('signature_link', [], $record, $i, $totalarray) !!}
				</td>
			

} !!}
		</tr>
	

}

	// Move fields of totalizable into the common array pos and val
	if (!empty($totalarray['totalizable']) && is_array($totalarray['totalizable'])) {
		foreach ($totalarray['totalizable'] as $keytotalizable => $valtotalizable) {
			$totalarray['pos'][$valtotalizable['pos']] = $keytotalizable;
			$totalarray['val'][$keytotalizable] = isset($valtotalizable['total']) ? $valtotalizable['total'] : 0;
		}
	}
	// Show total line
	if (isset($totalarray['pos'])) { !!}
		<tr>
		

$i = 0;
		while ($i < $totalarray['nbfield']) {
			$i++;
			if (!empty($totalarray['pos'][$i])) { !!}
				<td class="nowraponall essai">
					{!! price(!empty($totalarray['val'][$totalarray['pos'][$i]]) ? $totalarray['val'][$totalarray['pos'][$i]] : 0) !!}
				</td>
			

} else {
				if ($i == 1) { !!}
					<td>{!! $langs->trans("Total") ?></td>
				

} else { !!}
					<td></td>
				

}
			}
		} !!}
		</tr>
	

}
} else { // If no record found !!}
<tr><td colspan="{!! $formList->nbColumn ?>"><span class="opacitymedium">{!! $langs->trans("NoRecordFound") ?></span></td></tr>


} !!}