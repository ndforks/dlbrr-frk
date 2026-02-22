<?php
/* Copyright (C) 2009-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2024       Frédéric France      <frederic.france@free.fr>
 * Copyright (C) 2025       GitHub Copilot       AI-assisted refactoring
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

namespace App\Http\Controllers\Imports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

require_once DOL_DOCUMENT_ROOT.'/Core/lib/files.lib.php';
require_once DOL_DOCUMENT_ROOT.'/Imports/class/import.class.php';
require_once DOL_DOCUMENT_ROOT.'/Core/modules/import/modules_import.php';

/**
 * Controller for generating example import files
 */
class EmptyExampleController extends Controller
{
    /**
     * Generate and download example import file
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        global $db, $user, $langs;
        
        $langs->load("exports");
        
        $datatoimport = $request->input('datatoimport');
        $format = $request->input('format');
        
        if (empty($datatoimport)) {
            $user->loadRights();
            abort(400, 'Bad value for datatoimport.');
        }
        
        $filename = $langs->transnoentitiesnoconv("ExampleOfImportFile").'_'.$datatoimport.'.'.$format;
        
        $objimport = new \Import($db);
        $objimport->load_arrays($user, $datatoimport);
        
        $fieldstarget = $objimport->array_import_fields[0];
        $valuestarget = $objimport->array_import_examplevalues[0];
        
        $attachment = $request->boolean('attachment', true);
        $contenttype = $request->input('contenttype', dol_mimetype($format));
        $outputencoding = 'UTF-8';
        
        // Build header and content lines
        $headerlinefields = [];
        $contentlinevalues = [];
        
        foreach ($fieldstarget as $code => $label) {
            $withoutstar = preg_replace('/\*/', '', $fieldstarget[$code]);
            $headerlinefields[] = $langs->transnoentities($withoutstar).($withoutstar != $fieldstarget[$code] ? '*' : '').' ('.$code.')';
            $contentlinevalues[] = (isset($valuestarget[$code]) ? $valuestarget[$code] : '');
        }
        
        $content = $objimport->build_example_file($format, $headerlinefields, $contentlinevalues, $datatoimport);
        
        $headers = [];
        if ($contenttype) {
            $headers['Content-Type'] = $contenttype.($outputencoding ? '; charset='.$outputencoding : '');
        }
        if ($attachment) {
            $headers['Content-Disposition'] = 'attachment; filename="'.$filename.'"';
        }
        
        return response($content, 200, $headers);
    }
}
