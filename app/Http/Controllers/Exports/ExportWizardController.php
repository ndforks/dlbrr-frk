<?php
/* Copyright (C) 2005-2018 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@inodbox.com>
 * Copyright (C) 2012      Marcos García        <marcosgdf@gmail.com>
 * Copyright (C) 2012      Charles-Fr BENKE     <charles.fr@benke.fr>
 * Copyright (C) 2015      Juanjo Menent        <jmenent@2byte.es>
 * Copyright (C) 2024      Frédéric France      <frederic.france@free.fr>
 * Copyright (C) 2025      GitHub Copilot       AI-assisted refactoring
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

namespace App\Http\Controllers\Exports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

require_once DOL_DOCUMENT_ROOT.'/Core/class/html.formfile.class.php';
require_once DOL_DOCUMENT_ROOT.'/Core/class/html.formother.class.php';
require_once DOL_DOCUMENT_ROOT.'/Exports/class/export.class.php';
require_once DOL_DOCUMENT_ROOT.'/Core/modules/export/modules_export.php';
require_once DOL_DOCUMENT_ROOT.'/Core/lib/files.lib.php';

/**
 * Controller for Export Wizard
 * 
 * Handles multi-step export process
 */
class ExportWizardController extends Controller
{
    /**
     * Handle export wizard requests
     *
     * @param Request $request
     * @return View
     */
    public function __invoke(Request $request): View
    {
        global $db, $user, $langs;
        
        $langs->loadlangs(['admin', 'exports', 'other', 'users', 'companies', 'projects', 'suppliers', 'products', 'bank', 'bills']);
        
        // Entity icon mapping
        $entitytoicon = $this->getEntityToIcon();
        $entitytolang = $this->getEntityToLang();
        
        $action = $request->input('action', '');
        $step = $request->integer('step', 1);
        $datatoexport = $request->input('datatoexport', '');
        $format = $request->input('format', 'csv');
        
        // Initialize export object
        $obj = new \Export($db);
        $obj->load_arrays($user, $datatoexport);
        
        return view('exports.wizard', [
            'step' => $step,
            'action' => $action,
            'datatoexport' => $datatoexport,
            'format' => $format,
            'obj' => $obj,
            'entitytoicon' => $entitytoicon,
            'entitytolang' => $entitytolang,
        ]);
    }
    
    /**
     * Get entity to icon mapping
     *
     * @return array
     */
    private function getEntityToIcon(): array
    {
        return [
            'invoice'      => 'bill',
            'invoice_line' => 'bill',
            'order'        => 'order',
            'order_line'   => 'order',
            'propal'       => 'propal',
            'propal_line'  => 'propal',
            'intervention' => 'intervention',
            'inter_line'   => 'intervention',
            'member'       => 'user',
            'member_type'  => 'group',
            'subscription' => 'payment',
            'payment'      => 'payment',
            'tax'          => 'generic',
            'tax_type'     => 'generic',
            'other'        => 'generic',
            'account'      => 'account',
            'product'      => 'product',
            'virtualproduct' => 'product',
            'subproduct'   => 'product',
            'product_supplier_ref' => 'product',
            'stock'        => 'stock',
            'warehouse'    => 'stock',
            'batch'        => 'stock',
            'stockbatch'   => 'stock',
            'category'     => 'category',
            'securityevent' => 'generic',
            'shipment'     => 'sending',
            'shipment_line' => 'sending',
            'reception'    => 'sending',
            'reception_line' => 'sending',
            'expensereport' => 'trip',
            'expensereport_line' => 'trip',
            'holiday'      => 'holiday',
            'contract_line' => 'contract',
            'translation'  => 'generic',
            'bomm'         => 'bom',
            'bomline'      => 'bom',
            'conferenceorboothattendee' => 'contact',
            'inventory_line' => 'inventory',
            'mrp_line'     => 'mrp',
            'task_time'    => 'clock',
        ];
    }
    
    /**
     * Get entity to language mapping
     *
     * @return array
     */
    private function getEntityToLang(): array
    {
        return [
            'user'         => 'User',
            'company'      => 'Company',
            'contact'      => 'Contact',
            'member'       => 'Member',
            'subscription' => 'Subscription',
            'invoice'      => 'Bill',
            'order'        => 'Order',
            'propal'       => 'Proposal',
            'product'      => 'Product',
            'stock'        => 'Stock',
            'project'      => 'Project',
            'task'         => 'Task',
        ];
    }
}
