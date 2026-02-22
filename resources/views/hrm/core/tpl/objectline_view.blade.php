{{--
/* Copyright (C) 2010-2013	Regis Houssin		<regis.houssin@inodbox.com>
 * Copyright (C) 2010-2011	Laurent Destailleur	<eldy@users.sourceforge.net>
 * Copyright (C) 2012-2013	Christophe Battarel	<christophe.battarel@altairis.fr>
 * Copyright (C) 2012       Cédric Salvador     <csalvador@gpcsolutions.fr>
 * Copyright (C) 2012-2014  Raphaël Doursenaud  <rdoursenaud@gpcsolutions.fr>
 * Copyright (C) 2013		Florian Henry		<florian.henry@open-concept.pro>
 * Copyright (C) 2017		Juanjo Menent		<jmenent@2byte.es>
 * Copyright (C) 2021 Gauthier VERDOL <gauthier.verdol@atm-consulting.fr>
 * Copyright (C) 2021 Greg Rastklan <greg.rastklan@atm-consulting.fr>
 * Copyright (C) 2021 Jean-Pascal BOUDET <jean-pascal.boudet@atm-consulting.fr>
 * Copyright (C) 2021 Grégory BLEMAND <gregory.blemand@atm-consulting.fr>
 * Copyright (C) 2024-2025	MDW					<mdeweerd@users.noreply.github.com>
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
 *
 * Need to have the following variables defined:
 * $object (invoice, order, ...)
 * $conf
 * $langs
 * $dateSelector
 * $element     (used to test $user->rights->$element->creer)
 * $permtoedit  (used to replace test $user->rights->$element->creer)
 * $usemargins (0 to disable all margins columns, 1 to show according to margin setup)
 * $disableedit, $disablemove, $disableremove
 *
 * $text, $description, $line
 */
/**
 * @var string $action
 * @var int $i
 * @var int $permissiontoadd
 * @var CommonObjectLine $line
 * @var CommonObject $this
 * @var ?Conf $conf
 * @var Form $form
 * @var Translate $langs
 */
--}}
@php
// Protection to avoid direct call of template
if (empty($conf) || !is_object($conf)) {
	print "Error, template page can't be called as URL";
	exit(1);
}

// add html5 elements
$domData  = ' data-element="'.$line->element.'"';
$domData .= ' data-id="'.$line->id.'"';
$domData .= ' data-qty="'.$line->qty.'"';
$domData .= ' data-product_type="'.$line->product_type.'"';

$coldisplay = 0;
@endphp
<!-- BEGIN PHP TEMPLATE htm/core/tpl/objectline_view.tpl.php -->
<tr id="row-{{ $line->id }}" class="drag drop oddeven" {!! $domData !!}>
@if(getDolGlobalString('MAIN_VIEW_LINE_NUMBER'))
	<td class="linecolnum center"><span class="opacitymedium">@php $coldisplay++; echo ($i + 1); @endphp</span></td>
@endif
	<td class="linecollabel">@php $coldisplay++; @endphp<div id="line_{{ $line->id }}"></div>
@php
$skill = null;
$resSkill = 0;
if ($line->fk_skill > 0) {
	$skill = new Skill($this->db);
	$resSkill = $skill->fetch($line->fk_skill);
	if ($resSkill > 0) {
		print Skill::typeCodeToLabel($skill->skill_type);
	}
}
@endphp
	</td>
	<td>
@php
if ($line->fk_skill > 0 && $skill !== null) {
	print $skill->getNomUrl(1);
}
@endphp
	</td>

	<td class="linecoldescription minwidth300imp">@php $coldisplay++; @endphp
	</td>
	<td class="linecolrank nowrap right">@php $coldisplay++; @endphp
@php
	global $permissiontoadd;

// Show evaluation boxes
print displayRankInfos($line->rankorder, $line->fk_skill, 'TNote', ($this->status == 0 && $permissiontoadd) ? 'edit' : 'view');
@endphp
	</td>

@php
$coldisplay += 3;
@endphp
@if($action == 'selectlines')
	<td class="linecolcheck center"><input type="checkbox" class="linecheckbox" name="line_checkbox[{{ $i + 1 }}]" value="{{ $line->id }}" ></td>
@endif
</tr>

<!-- END PHP TEMPLATE objectline_view.tpl.php -->
