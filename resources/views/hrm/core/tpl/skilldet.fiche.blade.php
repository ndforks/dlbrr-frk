{{--
/* Copyright (C) 2024-2025	MDW						<mdeweerd@users.noreply.github.com>
 * Copyright (C) 2024-2025  Frédéric France         <frederic.france@free.fr>
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
 * @var ?Conf $conf
 * @var CommonObject $object
 * @var Form $form
 * @var Translate $langs
 *
 * @var int $colwidth
 * @var int $permission
 * @var string $typeofdata
 * @var string $moreparam
 * @var string $note_public
 * @var string $note_private
 * @var string $value_public
 * @var string $value_private
 */
--}}
@php
// Protection to avoid direct call of template
if (empty($conf) || !is_object($conf)) {
	print "Error, template page can't be called as URL";
	exit(1);
}

if (!empty($object->table_element_line)) {
	// Show object lines
	$result = $object->getLinesArray();
}
@endphp

<!-- BEGIN PHP TEMPLATE hrm/core/tpl/skilldet.fiche.tpl.php -->

<div class="tagtable border table-border tableforfield centpercent">
<div class="tagtr table-border-row">
@php
$editmode = (GETPOST('action', 'aZ09') == 'edit'.$note_public);
@endphp
<div class="tagtd tagtdnote tdtop{{ $editmode ? '' : ' sensiblehtmlcontent' }} table-key-border-col{{ empty($cssclass) ? '' : ' '.$cssclass }}"{{ $colwidth ? ' style="width: '.$colwidth.'%"' : '' }}>
{!! $form->editfieldkey("NotePublic", $note_public, $value_public, $object, $permission, $typeofdata, $moreparam, 0, 0) !!}
</div>
<div class="tagtd wordbreak table-val-border-col{{ $editmode ? '' : ' sensiblehtmlcontent' }}">
{!! $form->editfieldval("NotePublic", $note_public, $value_public, $object, $permission, $typeofdata, '', null, null, $moreparam, 1) !!}
</div>
</div>
@if(empty($user->socid))
<div class="tagtr table-border-row">
@php
$editmode = (GETPOST('action', 'aZ09') == 'edit'.$note_private);
@endphp
<div class="tagtd tagtdnote tdtop{{ $editmode ? '' : ' sensiblehtmlcontent' }} table-key-border-col{{ empty($cssclass) ? '' : ' '.$cssclass }}"{{ $colwidth ? ' style="width: '.$colwidth.'%"' : '' }}>
{!! $form->editfieldkey("NotePrivate", $note_private, $value_private, $object, $permission, $typeofdata, $moreparam, 0, 0) !!}
</div>
<div class="tagtd wordbreak table-val-border-col{{ $editmode ? '' : ' sensiblehtmlcontent' }}">
{!! $form->editfieldval("NotePrivate", $note_private, $value_private, $object, $permission, $typeofdata, '', null, null, $moreparam, 1) !!}
</div>
</div>
@endif
</div>
<!-- END PHP TEMPLATE NOTES-->
