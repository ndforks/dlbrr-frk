<?php
/* Copyright (C) 2005-2015 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2024       Frédéric France         <frederic.france@free.fr>
 * Copyright (C) 2025       GitHub Copilot          AI-assisted refactoring
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
use Illuminate\View\View;

require_once DOL_DOCUMENT_ROOT.'/Imports/class/import.class.php';
require_once DOL_DOCUMENT_ROOT.'/Exports/class/export.class.php';

/**
 * Controller for Import/Export home page
 */
class IndexController extends Controller
{
    /**
     * Display the import/export home page
     *
     * @param Request $request
     * @return View
     */
    public function __invoke(Request $request): View
    {
        global $db, $user, $langs;
        
        $langs->loadLangs(['exports', 'other']);
        
        if (!$user->socid == 0) {
            abort(403);
        }
        
        $export = new \Export($db);
        $export->load_arrays($user);
        
        $import = new \Import($db);
        $import->load_arrays($user);
        
        $usercanimport = false;
        $usercanexport = false;
        
        if (isModEnabled('import')) {
            $usercanimport = restrictedArea($user, 'import');
        }
        if (isModEnabled('export')) {
            $usercanexport = restrictedArea($user, 'export');
        }
        
        $title = "ImportExportArea";
        if (isModEnabled('import') && !isModEnabled('export')) {
            $title = "ImportArea";
        }
        if (!isModEnabled('import') && isModEnabled('export')) {
            $title = "ExportsArea";
        }
        
        // List of available import formats
        $importFormats = [];
        if (isModEnabled('import')) {
            include_once DOL_DOCUMENT_ROOT.'/Core/modules/import/modules_import.php';
            $model = new \ModeleImports();
            $list = $model->listOfAvailableImportFormat($db);
            
            foreach ($list as $key) {
                $importFormats[] = [
                    'key' => $key,
                    'label' => $model->getDriverLabelForKey($key),
                    'desc' => $model->getDriverDescForKey($key),
                    'lib' => $model->getLibLabelForKey($key),
                    'version' => $model->getLibVersionForKey($key),
                    'picto' => $model->getPictoForKey($key),
                ];
            }
        }
        
        // List of available export formats
        $exportFormats = [];
        if (isModEnabled('export')) {
            include_once DOL_DOCUMENT_ROOT.'/Core/modules/export/modules_export.php';
            $modelExport = new \ModeleExports($db);
            $liste = $modelExport->listOfAvailableExportFormat($db);
            
            foreach ($liste as $key => $val) {
                $label = $liste[$key];
                if (preg_match('/__\(Disabled\)__/', $label)) {
                    $label = preg_replace('/__\(Disabled\)__/', '('.$langs->transnoentitiesnoconv("Disabled").')', $label);
                }
                
                $exportFormats[] = [
                    'key' => $key,
                    'label' => $label,
                    'desc' => $modelExport->getDriverDescForKey($key),
                    'lib' => $modelExport->getLibLabelForKey($key),
                    'version' => $modelExport->getLibVersionForKey($key),
                    'picto' => $modelExport->getPictoForKey($key),
                ];
            }
        }
        
        return view('imports.index', [
            'title' => $title,
            'importFormats' => $importFormats,
            'exportFormats' => $exportFormats,
            'import' => $import,
            'export' => $export,
            'usercanimport' => $usercanimport,
            'usercanexport' => $usercanexport,
        ]);
    }
}
