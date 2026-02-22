<?php
/* Copyright (C) 2003      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Jean-Louis Bergamo   <jlb@j1b.org>
 * Copyright (C) 2006-2017 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2024-2025 MDW                  <mdeweerd@users.noreply.github.com>
 * Copyright (C) 2024      Frédéric France      <frederic.france@free.fr>
 * Copyright (C) 2025      William Mead         <william@m34d.com>
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

namespace App\Http\Controllers\Barcode;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;

require_once DOL_DOCUMENT_ROOT.'/Core/lib/format_cards.lib.php';
require_once DOL_DOCUMENT_ROOT.'/Core/lib/files.lib.php';
require_once DOL_DOCUMENT_ROOT.'/Core/modules/printsheet/modules_labels.php';
require_once DOL_DOCUMENT_ROOT.'/Core/class/genericobject.class.php';
require_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';
require_once DOL_DOCUMENT_ROOT.'/product/class/product.class.php';

/**
 * Controller for printing barcode sheets/labels
 * 
 * Handles barcode sheet generation with various label formats
 */
class PrintSheetController extends Controller
{
    /**
     * Handle printsheet requests
     *
     * @param Request $request
     * @return View|Response
     */
    public function __invoke(Request $request): View|Response
    {
        global $db, $user, $langs, $conf, $hookmanager;
        
        // Load translations
        $langs->loadLangs(['admin', 'members', 'errors']);
        
        // Security check
        if (!isModEnabled('barcode')) {
            abort(403);
        }
        
        if (!$user->hasRight('barcode', 'read')) {
            abort(403);
        }
        
        // Initialize hooks
        $hookmanager->initHooks(['printsheettools']);
        
        $mode = $request->input('mode');
        $action = $request->input('action');
        
        // Execute hooks
        $parameters = [];
        $object = new \stdClass();
        $reshook = $hookmanager->executeHooks('doActions', $parameters, $object, $action);
        if ($reshook < 0) {
            setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');
        }
        
        if (empty($reshook)) {
            // Handle product selection
            if ($request->input('submitproduct')) {
                return $this->handleProductSubmit($request);
            }
            
            // Handle thirdparty selection
            if ($request->input('submitthirdparty')) {
                return $this->handleThirdpartySubmit($request);
            }
            
            // Handle PDF generation
            if ($action === 'builddoc' && $mode === 'label') {
                return $this->generatePDF($request);
            }
        }
        
        // Show form by default
        return $this->showForm($request);
    }
    
    /**
     * Show barcode printsheet form
     *
     * @param Request $request
     * @return View
     */
    private function showForm(Request $request): View
    {
        global $db, $langs, $conf, $_Avery_Labels;
        
        $producttmp = new \Product($db);
        $thirdpartytmp = new \Societe($db);
        
        // Get form data from request
        $forbarcode = $request->input('forbarcode');
        $fk_barcode_type = $request->integer('fk_barcode_type', 0);
        $modellabel = $request->input('modellabel');
        $numberofsticker = $request->integer('numberofsticker', 10);
        $productid = $request->integer('productid', 0);
        $socid = $request->integer('socid', 0);
        $selectorforbarcode = $request->input('selectorforbarcode');
        
        $label_product_ref_option = $request->has('label_product_ref_option');
        $label_product_label_option = $request->has('label_product_label_option');
        
        $label_product_ref = $request->input('label_product_ref');
        
        if (getDolGlobalString('MAIN_SECURITY_ALLOW_UNSECURED_REF_LABELS')) {
            $security_check = 'nohtml';
        } else {
            $security_check = !getDolGlobalString('MAIN_SECURITY_ALLOW_UNSECURED_LABELS_WITH_HTML') ? 'alphanohtml' : 'restricthtml';
        }
        $label_product_label = $request->has('label_product_label') ? GETPOST('label_product_label', $security_check) : null;
        
        // Load product if selected
        if ($productid > 0) {
            $producttmp->fetch($productid);
        }
        
        // Load thirdparty if selected
        if ($socid > 0) {
            $thirdpartytmp->fetch($socid);
        }
        
        // Build array of label formats
        $arrayoflabels = [];
        foreach (array_keys($_Avery_Labels) as $codecards) {
            $arrayoflabels[$codecards] = $_Avery_Labels[$codecards]['name'];
        }
        asort($arrayoflabels);
        
        return view('barcode.printsheet', [
            'forbarcode' => $forbarcode,
            'fk_barcode_type' => $fk_barcode_type,
            'modellabel' => $modellabel ?: getDolGlobalString('ADHERENT_ETIQUETTE_TYPE'),
            'numberofsticker' => $numberofsticker,
            'arrayoflabels' => $arrayoflabels,
            'producttmp' => $producttmp,
            'thirdpartytmp' => $thirdpartytmp,
            'selectorforbarcode' => $selectorforbarcode,
            'label_product_ref' => $label_product_ref,
            'label_product_label' => $label_product_label,
            'label_product_ref_option' => $label_product_ref_option,
            'label_product_label_option' => $label_product_label_option,
        ]);
    }
    
    /**
     * Handle product selection form submission
     *
     * @param Request $request
     * @return View
     */
    private function handleProductSubmit(Request $request): View
    {
        global $db, $langs;
        
        $producttmp = new \Product($db);
        $productid = $request->integer('productid', 0);
        
        $forbarcode = $request->input('forbarcode');
        $fk_barcode_type = $request->integer('fk_barcode_type', 0);
        $label_product_ref = $request->input('label_product_ref');
        $label_product_label = $request->input('label_product_label');
        $label_product_ref_option = $request->has('label_product_ref_option');
        $label_product_label_option = $request->has('label_product_label_option');
        
        if ($productid > 0) {
            $result = $producttmp->fetch($productid);
            if ($result < 0) {
                setEventMessage($producttmp->error, 'errors');
            } else {
                $forbarcode = $producttmp->barcode;
                $fk_barcode_type = $producttmp->barcode_type;
                
                if (empty($fk_barcode_type) && getDolGlobalString('PRODUIT_DEFAULT_BARCODE_TYPE')) {
                    $fk_barcode_type = getDolGlobalString('PRODUIT_DEFAULT_BARCODE_TYPE');
                }
                
                if (empty($forbarcode) || empty($fk_barcode_type)) {
                    setEventMessages($langs->trans("DefinitionOfBarCodeForProductNotComplete", $producttmp->getNomUrl()), null, 'warnings');
                }
                
                if (empty($label_product_ref) && $label_product_ref_option) {
                    $label_product_ref = $producttmp->ref;
                }
                
                if (empty($label_product_label) && $label_product_label_option) {
                    $label_product_label = $producttmp->label;
                }
            }
        }
        
        // Return to form with updated data
        return $this->showForm($request->merge([
            'forbarcode' => $forbarcode,
            'fk_barcode_type' => $fk_barcode_type,
            'label_product_ref' => $label_product_ref,
            'label_product_label' => $label_product_label,
            'productid' => $productid,
        ]));
    }
    
    /**
     * Handle thirdparty selection form submission
     *
     * @param Request $request
     * @return View
     */
    private function handleThirdpartySubmit(Request $request): View
    {
        global $db, $langs;
        
        $thirdpartytmp = new \Societe($db);
        $socid = $request->integer('socid', 0);
        
        $forbarcode = $request->input('forbarcode');
        $fk_barcode_type = $request->integer('fk_barcode_type', 0);
        
        if ($socid > 0) {
            $thirdpartytmp->fetch($socid);
            $forbarcode = $thirdpartytmp->barcode;
            $fk_barcode_type = $thirdpartytmp->barcode_type_code;
            
            if (empty($fk_barcode_type) && getDolGlobalString('GENBARCODE_BARCODETYPE_THIRDPARTY')) {
                $fk_barcode_type = getDolGlobalString('GENBARCODE_BARCODETYPE_THIRDPARTY');
            }
            
            if (empty($forbarcode) || empty($fk_barcode_type)) {
                setEventMessages($langs->trans("DefinitionOfBarCodeForThirdpartyNotComplete", $thirdpartytmp->getNomUrl()), null, 'warnings');
            }
        }
        
        // Return to form with updated data
        return $this->showForm($request->merge([
            'forbarcode' => $forbarcode,
            'fk_barcode_type' => $fk_barcode_type,
            'socid' => $socid,
        ]));
    }
    
    /**
     * Generate PDF with barcode labels
     *
     * @param Request $request
     * @return Response|View
     */
    private function generatePDF(Request $request): Response|View
    {
        global $db, $user, $langs, $conf, $mysoc;
        
        $error = 0;
        $mesg = '';
        
        $forbarcode = $request->input('forbarcode');
        $fk_barcode_type = $request->integer('fk_barcode_type', 0);
        $modellabel = $request->input('modellabel');
        $numberofsticker = $request->integer('numberofsticker', 0);
        
        // Validate inputs
        if (empty($forbarcode)) {
            setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("BarcodeValue")), null, 'errors');
            $error++;
        }
        
        $MAXLENGTH = 51200; // Limit set to 50Ko
        if (dol_strlen($forbarcode) > $MAXLENGTH) {
            setEventMessages($langs->trans("ErrorFieldTooLong", $langs->transnoentitiesnoconv("BarcodeValue")).' ('.$langs->trans("RequireXStringMax", $MAXLENGTH).')', null, 'errors');
            $error++;
        }
        
        if (empty($fk_barcode_type)) {
            setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("BarcodeType")), null, 'errors');
            $error++;
        }
        
        if ($error) {
            return $this->showForm($request);
        }
        
        // Get barcode type information
        $stdobject = new \GenericObject($db);
        $stdobject->barcode_type = $fk_barcode_type;
        $result = $stdobject->fetchBarCode();
        if ($result <= 0) {
            setEventMessages('Failed to get bar code type information '.$stdobject->error, $stdobject->errors, 'errors');
            return $this->showForm($request);
        }
        
        $code = $forbarcode;
        $generator = $stdobject->barcode_type_coder;
        $encoding = strtoupper($stdobject->barcode_type_code);
        
        $diroutput = $conf->barcode->dir_temp;
        dol_mkdir($diroutput);
        
        // Generate barcode image
        $barcodeimage = '';
        $template = '';
        $is2d = false;
        
        $dirbarcode = array_merge(["/core/modules/barcode/doc/"], $conf->modules_parts['barcode'] ?? []);
        
        foreach ($dirbarcode as $reldir) {
            $dir = dol_buildpath($reldir, 0);
            $newdir = dol_osencode($dir);
            
            if (!is_dir($newdir)) {
                continue;
            }
            
            $result = @include_once $newdir.$generator.'.modules.php';
            if ($result) {
                break;
            }
        }
        
        $classname = "mod".ucfirst($generator);
        $module = new $classname($db);
        
        if ($generator != 'tcpdfbarcode') {
            $template = 'standardlabel';
            if ($module->encodingIsSupported($encoding)) {
                $barcodeimage = $conf->barcode->dir_temp.'/barcode_'.$code.'_'.$encoding.'.png';
                dol_delete_file($barcodeimage);
                $result = $module->writeBarCode($code, $encoding, 'Y', 4, 1);
                if ($result <= 0 || !dol_is_file($barcodeimage)) {
                    setEventMessages('Failed to generate image file of barcode for code='.$code.' encoding='.$encoding.' file='.basename($barcodeimage), null, 'errors');
                    setEventMessages($module->error, null, 'errors');
                    return $this->showForm($request);
                }
            } else {
                setEventMessages("Error, encoding ".$encoding." is not supported by encoder ".$generator.'. You must choose another barcode type or install a barcode generation engine that support '.$encoding, null, 'errors');
                return $this->showForm($request);
            }
        } else {
            $template = 'tcpdflabel';
            $encoding = $module->getTcpdfEncodingType($encoding);
            $is2d = $module->is2d;
        }
        
        // Build substitution array
        $now = dol_now();
        $year = dol_print_date($now, '%Y');
        $month = dol_print_date($now, '%m');
        $day = dol_print_date($now, '%d');
        
        global $dolibarr_main_url_root;
        $substitutionarray = [
            '%LOGIN%' => $user->login,
            '%COMPANY%' => $mysoc->name,
            '%ADDRESS%' => $mysoc->address,
            '%ZIP%' => $mysoc->zip,
            '%TOWN%' => $mysoc->town,
            '%COUNTRY%' => $mysoc->country,
            '%COUNTRY_CODE%' => $mysoc->country_code,
            '%EMAIL%' => $mysoc->email,
            '%YEAR%' => $year,
            '%MONTH%' => $month,
            '%DAY%' => $day,
            '%DOL_MAIN_URL_ROOT%' => DOL_MAIN_URL_ROOT,
            '%SERVER%' => $dolibarr_main_url_root,
            '__LOGIN__' => $user->login,
            '__COMPANY__' => $mysoc->name,
            '__ADDRESS__' => $mysoc->address,
            '__ZIP__' => $mysoc->zip,
            '__TOWN__' => $mysoc->town,
            '__COUNTRY__' => $mysoc->country,
            '__COUNTRY_CODE__' => $mysoc->country_code,
            '__EMAIL__' => $mysoc->email,
            '__YEAR__' => $year,
            '__MONTH__' => $month,
            '__DAY__' => $day,
            '__DOL_MAIN_URL_ROOT__' => DOL_MAIN_URL_ROOT,
            '__SERVER__' => $dolibarr_main_url_root,
        ];
        complete_substitutions_array($substitutionarray, $langs);
        
        // Get product labels if applicable
        $productid = $request->integer('productid', 0);
        $label_product_ref = $request->input('label_product_ref');
        $label_product_label = $request->input('label_product_label');
        $label_product_ref_option = $request->has('label_product_ref_option');
        $label_product_label_option = $request->has('label_product_label_option');
        
        if ($productid > 0) {
            $producttmp = new \Product($db);
            $result = $producttmp->fetch($productid);
            if ($result > 0) {
                if (empty($label_product_ref) && $label_product_ref_option) {
                    $label_product_ref = $producttmp->ref;
                }
                if (empty($label_product_label) && $label_product_label_option) {
                    $label_product_label = $producttmp->label;
                }
            }
        }
        
        // Build array of records for labels
        $txtforsticker = "%PHOTO%";
        $textleft = make_substitutions(getDolGlobalString('BARCODE_LABEL_LEFT_TEXT', $txtforsticker), $substitutionarray);
        
        if ($productid > 0 && $label_product_ref_option) {
            $textheader = $label_product_ref;
        } else {
            $textheader = make_substitutions(getDolGlobalString('BARCODE_LABEL_HEADER_TEXT'), $substitutionarray);
        }
        
        if ($productid > 0 && $label_product_label_option) {
            $textfooter = $label_product_label;
        } else {
            $textfooter = make_substitutions(getDolGlobalString('BARCODE_LABEL_FOOTER_TEXT'), $substitutionarray);
        }
        
        $textright = make_substitutions(getDolGlobalString('BARCODE_LABEL_RIGHT_TEXT'), $substitutionarray);
        $forceimgscalewidth = getDolGlobalString('BARCODE_FORCEIMGSCALEWIDTH', 1);
        $forceimgscaleheight = getDolGlobalString('BARCODE_FORCEIMGSCALEHEIGHT', 1);
        
        $MAXSTICKERS = 1000;
        $arrayofrecords = [];
        
        if ($numberofsticker <= $MAXSTICKERS) {
            for ($i = 0; $i < $numberofsticker; $i++) {
                $arrayofrecords[] = [
                    'textleft' => $textleft,
                    'textheader' => $textheader,
                    'textfooter' => $textfooter,
                    'textright' => $textright,
                    'code' => $code,
                    'encoding' => $encoding,
                    'is2d' => $is2d,
                    'photo' => !empty($barcodeimage) ? $barcodeimage : ''
                ];
            }
        } else {
            setEventMessages($langs->trans("ErrorQuantityIsLimitedTo", $MAXSTICKERS), null, 'errors');
            return $this->showForm($request);
        }
        
        // Validate we have records
        if (!count($arrayofrecords)) {
            setEventMessages($langs->trans("ErrorRecordNotFound"), null, 'errors');
            return $this->showForm($request);
        }
        
        if (empty($modellabel) || $modellabel == '-1') {
            setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("DescADHERENT_ETIQUETTE_TYPE")), null, 'errors');
            return $this->showForm($request);
        }
        
        // Generate PDF
        $outfile = $langs->trans("BarCode").'_sheets_'.dol_print_date(dol_now(), 'dayhourlog').'.pdf';
        $outputlangs = $langs;
        
        $previousConf = getDolGlobalInt('TCPDF_THROW_ERRORS_INSTEAD_OF_DIE');
        $conf->global->TCPDF_THROW_ERRORS_INSTEAD_OF_DIE = 1;
        
        try {
            $result = doc_label_pdf_create($db, $arrayofrecords, $modellabel, $outputlangs, (string) $diroutput, (string) $template, dol_sanitizeFileName($outfile));
        } catch (\Exception $e) {
            setEventMessages($langs->trans('ErrorGeneratingBarcode'), null, 'errors');
            $conf->global->TCPDF_THROW_ERRORS_INSTEAD_OF_DIE = $previousConf;
            return $this->showForm($request);
        }
        
        $conf->global->TCPDF_THROW_ERRORS_INSTEAD_OF_DIE = $previousConf;
        
        if ($result <= 0) {
            setEventMessages('Error '.$result, null, 'errors');
            return $this->showForm($request);
        }
        
        // PDF was sent directly to output, close database and exit
        $db->close();
        exit;
    }
}
