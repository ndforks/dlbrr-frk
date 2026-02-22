{{-- Blade template version --}}
<!-- BEGIN TEMPLATE resource_view.tpl.php -->
{{--
/* Copyright (C) 2024		MDW	                    <mdeweerd@users.noreply.github.com>
 * Copyright (C) 2024       Frédéric France         <frederic.france@free.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * @var Conf $conf
 * @var DoliDB $db
 * @var Translate $langs
 *
 * @var string $element
 * @var int $element_id
 * @var string $mode
 * @var string $resource_type
 * @var array<array{rowid:int,resource_id:int,resource_type:string,busy:int<0,1>,mandatory:int<0,1>}> $linked_resources
 */
--}}
@php
// Protection to avoid direct call of template
if (empty($conf) || !is_object($conf)) {
	print "Error, template page can't be called as URL";
	exit(1);
}

'
@phan-var-force string $element
@phan-var-force int $element_id
@phan-var-force string $resource_type
@phan-var-force array<array{rowid:int,resource_id:int,resource_type:string,busy:int<0,1>,mandatory:int<0,1>}> $linked_resources
';

$form = new Form($db);
@endphp

<div class="tagtable centpercent noborder allwidth">

<form method="POST" class="tagtable centpercent noborder borderbottom allwidth">

<div class="tagtr liste_titre">
<div class="tagtd liste_titre">{{ $langs->trans('Resource') }}</div>
<div class="tagtd liste_titre">{{ $langs->trans('Type') }}</div>
<div class="tagtd liste_titre center">{{ $langs->trans('Busy') }}</div>
<div class="tagtd liste_titre center">{{ $langs->trans('Mandatory') }}</div>
<div class="tagtd liste_titre"></div>
</div>

<input type="hidden" name="token" value="{{ newToken() }}" />
<input type="hidden" name="id" value="{{ $element_id }}" />
<input type="hidden" name="action" value="update_linked_resource" />
<input type="hidden" name="resource_type" value="{{ $resource_type }}" />

@if ((array) $linked_resources && count($linked_resources) > 0)
	@foreach ($linked_resources as $linked_resource)
		@php
			$object_resource = fetchObjectByElement($linked_resource['resource_id'], $linked_resource['resource_type']);
		@endphp

		@if ($mode == 'edit' && $linked_resource['rowid'] == GETPOSTINT('lineid'))
			<div class="tagtr oddeven">
			<input type="hidden" name="lineid" value="{{ $linked_resource['rowid'] }}" />
			<input type="hidden" name="element" value="{{ $element }}" />
			<input type="hidden" name="element_id" value="{{ $element_id }}" />

			<div class="tagtd">{!! $object_resource->getNomUrl(1) !!}</div>
			<div class="tagtd">{{ $object_resource->type_label }}</div>
			<div class="tagtd center">{!! $form->selectyesno('busy', $linked_resource['busy'] ? 1 : 0, 1) !!}</div>
			<div class="tagtd center">{!! $form->selectyesno('mandatory', $linked_resource['mandatory'] ? 1 : 0, 1) !!}</div>
			<div class="tagtd right"><input type="submit" class="button" value="{{ $langs->trans("Update") }}"></div>
			</div>
		@else
			@php
				$class = '';
				if ($linked_resource['rowid'] == GETPOSTINT('lineid')) {
					$class = 'highlight';
				}
			@endphp

			<div class="tagtr oddeven{{ $class ? ' '.$class : '' }}">

			<div class="tagtd">
			{!! $object_resource->getNomUrl(1) !!}
			</div>

			<div class="tagtd">
			{{ $object_resource->type_label }}
			</div>

			<div class="tagtd center">
			{!! yn($linked_resource['busy']) !!}
			</div>

			<div class="tagtd center">
			{!! yn($linked_resource['mandatory']) !!}
			</div>

			<div class="tagtd right">
			<a class="editfielda marginleftonly marginrightonly" href="{{ $_SERVER['PHP_SELF'] }}?mode=edit&token={{ newToken() }}&resource_type={{ $linked_resource['resource_type'] }}&element={{ $element }}&element_id={{ $element_id }}&lineid={{ $linked_resource['rowid'] }}">
			{!! img_edit() !!}
			</a>
			&nbsp;
			<a class="marginleftonly marginrightonly" href="{{ $_SERVER['PHP_SELF'] }}?action=delete_resource&token={{ newToken() }}&id={{ $linked_resource['resource_id'] }}&element={{ $element }}&element_id={{ $element_id }}&lineid={{ $linked_resource['rowid'] }}">
			{!! img_picto($langs->trans("Unlink"), 'unlink') !!}
			</a>
			</div>

			</div>
		@endif
	@endforeach
@else
	<div class="tagtr oddeven">
	<div class="tagtd opacitymedium">{{ $langs->trans('NoResourceLinked') }}</div>
	<div class="tagtd opacitymedium"></div>
	<div class="tagtd opacitymedium"></div>
	<div class="tagtd opacitymedium"></div>
	<div class="tagtd opacitymedium"></div>
	</div>
@endif

</form>

</div>

<!-- END TEMPLATE resource_view.tpl.php -->
