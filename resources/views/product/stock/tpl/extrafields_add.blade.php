{{-- Blade version of template
/* Copyright (C) 2014	    Maxime Kohlhaas		    <support@atm-consulting.fr>
 * Copyright (C) 2014	    Juanjo Menent		    <jmenent@2byte.es>
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
 * $action
 * $conf
 * $langs
 *
 * $parameters
 * $cols
 */
--}}

<!-- BEGIN BLADE TEMPLATE extrafields_add.blade.php -->

@php
$parameters = $parameters ?? array();
$reshook = $hookmanager->executeHooks('formObjectOptions', $parameters, $object, $action);
@endphp

{!! $hookmanager->resPrint !!}

@if (empty($reshook))
    @php
        $params = array();
        $params['cols'] = array_key_exists('colspanvalue', $parameters) ? $parameters['colspanvalue'] : '';
        if (!empty($parameters['tdclass'])) {
            $params['tdclass'] = $parameters['tdclass'];
        }
        if (!empty($parameters['tpl_context'])) {
            $params['tpl_context'] = $parameters['tpl_context'];
        }
    @endphp
    {!! $object->showOptionals($extrafields, 'create', $params) !!}
@endif

<!-- END BLADE TEMPLATE extrafields_add.blade.php -->
