<?php
/* Copyright (C) 2014-2022 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2018      Ferran Marcet        <fmarcet@2byte.es>
 * Copyright (C) 2024      MDW                  <mdeweerd@users.noreply.github.com>
 * Copyright (C) 2024-2025 Frédéric France      <frederic.france@free.fr>
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
use Illuminate\Http\RedirectResponse;

require_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';
require_once DOL_DOCUMENT_ROOT.'/product/class/product.class.php';

/**
 * Controller for mass barcode initialization
 * 
 * Handles barcode initialization for products and third parties
 */
class CodeInitController extends Controller
{
    /**
     * Handle barcode initialization requests
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function __invoke(Request $request): View|RedirectResponse
    {
        global $db, $user, $langs, $conf;
        
        // Load translations
        $langs->loadLangs(['admin', 'members', 'errors', 'other']);
        
        // Security check
        if (!isModEnabled('barcode')) {
            abort(403);
        }
        
        if (empty($user->admin)) {
            abort(403);
        }
        
        $action = $request->input('action', '');
        $maxperinit = getDolGlobalInt('BARCODE_INIT_MAX', 1000);
        
        return match($action) {
            'initbarcodeproducts' => $this->initBarcodeProducts($request, $maxperinit),
            'initbarcodethirdparties' => $this->initBarcodeThirdparties($request, $maxperinit),
            default => $this->showForm($request, $maxperinit),
        };
    }
    
    /**
     * Show barcode initialization form
     *
     * @param Request $request
     * @param int $maxperinit
     * @return View
     */
    private function showForm(Request $request, int $maxperinit): View
    {
        global $db, $langs, $conf;
        
        $modBarCodeProduct = $this->loadBarcodeModule('product');
        $modBarCodeThirdparty = $this->loadBarcodeModule('thirdparty');
        
        // Get product statistics
        $productStats = $this->getProductStats();
        
        // Get thirdparty statistics
        $thirdpartyStats = $this->getThirdpartyStats();
        
        return view('barcode.codeinit', [
            'maxperinit' => $maxperinit,
            'modBarCodeProduct' => $modBarCodeProduct,
            'modBarCodeThirdparty' => $modBarCodeThirdparty,
            'productStats' => $productStats,
            'thirdpartyStats' => $thirdpartyStats,
            'dol_openinpopup' => $request->input('dol_openinpopup'),
        ]);
    }
    
    /**
     * Initialize barcodes for products
     *
     * @param Request $request
     * @param int $maxperinit
     * @return RedirectResponse
     */
    private function initBarcodeProducts(Request $request, int $maxperinit): RedirectResponse
    {
        global $db, $user, $langs;
        
        if (!$user->hasRight('produit', 'lire')) {
            abort(403);
        }
        
        $modBarCodeProduct = $this->loadBarcodeModule('product');
        $eraseallproductbarcode = $request->input('eraseallproductbarcode');
        
        if (!is_object($modBarCodeProduct)) {
            setEventMessages($langs->trans("NoBarcodeNumberingTemplateDefined"), null, 'errors');
            return redirect()->route('barcode.codeinit');
        }
        
        $productstatic = new \Product($db);
        $db->begin();
        
        try {
            if (!empty($eraseallproductbarcode)) {
                $this->eraseAllProductBarcodes();
                setEventMessages($langs->trans("AllBarcodeReset"), null, 'mesgs');
            } else {
                $nbok = $this->generateProductBarcodes($modBarCodeProduct, $productstatic, $maxperinit);
                setEventMessages($langs->trans("RecordsModified", $nbok), null, 'mesgs');
            }
            
            $db->commit();
        } catch (\Exception $e) {
            $db->rollback();
            abort(500, $e->getMessage());
        }
        
        return redirect()->route('barcode.codeinit');
    }
    
    /**
     * Initialize barcodes for third parties
     *
     * @param Request $request
     * @param int $maxperinit
     * @return RedirectResponse
     */
    private function initBarcodeThirdparties(Request $request, int $maxperinit): RedirectResponse
    {
        global $db, $user, $langs;
        
        if (!$user->hasRight('societe', 'lire')) {
            abort(403);
        }
        
        $modBarCodeThirdparty = $this->loadBarcodeModule('thirdparty');
        $eraseallthirdpartybarcode = $request->input('eraseallthirdpartybarcode');
        
        if (!is_object($modBarCodeThirdparty)) {
            setEventMessages($langs->trans("NoBarcodeNumberingTemplateDefined"), null, 'errors');
            return redirect()->route('barcode.codeinit');
        }
        
        $thirdpartystatic = new \Societe($db);
        $db->begin();
        
        try {
            if (!empty($eraseallthirdpartybarcode)) {
                $this->eraseAllThirdpartyBarcodes();
                setEventMessages($langs->trans("AllBarcodeReset"), null, 'mesgs');
            } else {
                $nbok = $this->generateThirdpartyBarcodes($modBarCodeThirdparty, $thirdpartystatic, $maxperinit);
                setEventMessages($langs->trans("RecordsModified", $nbok), null, 'mesgs');
            }
            
            $db->commit();
        } catch (\Exception $e) {
            $db->rollback();
            abort(500, $e->getMessage());
        }
        
        return redirect()->route('barcode.codeinit');
    }
    
    /**
     * Load barcode numbering module
     *
     * @param string $type 'product' or 'thirdparty'
     * @return object|null
     */
    private function loadBarcodeModule(string $type): ?object
    {
        global $conf;
        
        $configKey = $type === 'product' ? 'BARCODE_PRODUCT_ADDON_NUM' : 'BARCODE_THIRDPARTY_ADDON_NUM';
        
        if (!getDolGlobalString($configKey)) {
            return null;
        }
        
        $dirbarcodenum = array_merge(['/core/modules/barcode/'], $conf->modules_parts['barcode'] ?? []);
        
        foreach ($dirbarcodenum as $dirroot) {
            $dir = dol_buildpath($dirroot, 0);
            $handle = @opendir($dir);
            
            if (is_resource($handle)) {
                while (($file = readdir($handle)) !== false) {
                    $pattern = $type === 'product' ? '/^mod_barcode_product_.*php$/' : '/^mod_barcode_thirdparty_.*php$/';
                    
                    if (preg_match($pattern, $file)) {
                        $file = substr($file, 0, dol_strlen($file) - 4);
                        
                        if ($type === 'product' && $file !== getDolGlobalString($configKey)) {
                            continue;
                        }
                        
                        try {
                            dol_include_once($dirroot.$file.'.php');
                            closedir($handle);
                            return new $file();
                        } catch (\Exception $e) {
                            dol_syslog($e->getMessage(), LOG_ERR);
                        }
                    }
                }
                closedir($handle);
            }
        }
        
        return null;
    }
    
    /**
     * Get product statistics for display
     *
     * @return array
     */
    private function getProductStats(): array
    {
        global $db;
        
        $stats = [
            'nbWithoutBarcode' => 0,
            'nbTotal' => 0,
        ];
        
        // Count products without barcode
        $sql = "SELECT count(rowid) as nb FROM ".MAIN_DB_PREFIX."product";
        $sql .= " WHERE (barcode IS NULL OR barcode = '')";
        $sql .= " AND entity IN (".getEntity('product').")";
        
        $resql = $db->query($sql);
        if ($resql) {
            $obj = $db->fetch_object($resql);
            $stats['nbWithoutBarcode'] = $obj->nb;
        }
        
        // Count total products
        $sql = "SELECT count(rowid) as nb FROM ".MAIN_DB_PREFIX."product";
        $sql .= " WHERE entity IN (".getEntity('product').")";
        
        $resql = $db->query($sql);
        if ($resql) {
            $obj = $db->fetch_object($resql);
            $stats['nbTotal'] = $obj->nb;
        }
        
        return $stats;
    }
    
    /**
     * Get thirdparty statistics for display
     *
     * @return array
     */
    private function getThirdpartyStats(): array
    {
        global $db;
        
        $stats = [
            'nbWithoutBarcode' => 0,
            'nbTotal' => 0,
        ];
        
        // Count thirdparties without barcode
        $sql = "SELECT count(rowid) as nb FROM ".MAIN_DB_PREFIX."societe";
        $sql .= " WHERE (barcode IS NULL OR barcode = '')";
        $sql .= " AND entity IN (".getEntity('societe').")";
        
        $resql = $db->query($sql);
        if ($resql) {
            $obj = $db->fetch_object($resql);
            $stats['nbWithoutBarcode'] = $obj->nb;
        }
        
        // Count total thirdparties
        $sql = "SELECT count(rowid) as nb FROM ".MAIN_DB_PREFIX."societe";
        $sql .= " WHERE entity IN (".getEntity('societe').")";
        
        $resql = $db->query($sql);
        if ($resql) {
            $obj = $db->fetch_object($resql);
            $stats['nbTotal'] = $obj->nb;
        }
        
        return $stats;
    }
    
    /**
     * Erase all product barcodes
     *
     * @return void
     */
    private function eraseAllProductBarcodes(): void
    {
        global $db;
        
        $sql = "UPDATE ".MAIN_DB_PREFIX."product";
        $sql .= " SET barcode = NULL";
        $sql .= " WHERE barcode IS NOT NULL";
        $sql .= " AND entity IN (".getEntity('product').")";
        
        $resql = $db->query($sql);
        if (!$resql) {
            throw new \Exception('Failed to erase product barcodes');
        }
    }
    
    /**
     * Erase all thirdparty barcodes
     *
     * @return void
     */
    private function eraseAllThirdpartyBarcodes(): void
    {
        global $db;
        
        $sql = "UPDATE ".MAIN_DB_PREFIX."societe";
        $sql .= " SET barcode = NULL";
        $sql .= " WHERE barcode IS NOT NULL";
        $sql .= " AND entity IN (".getEntity('societe').")";
        
        $resql = $db->query($sql);
        if (!$resql) {
            throw new \Exception('Failed to erase thirdparty barcodes');
        }
    }
    
    /**
     * Generate barcodes for products
     *
     * @param object $modBarCodeProduct
     * @param \Product $productstatic
     * @param int $maxperinit
     * @return int Number of records modified
     */
    private function generateProductBarcodes(object $modBarCodeProduct, \Product $productstatic, int $maxperinit): int
    {
        global $db, $user;
        
        $sql = "SELECT rowid, ref, fk_product_type";
        $sql .= " FROM ".MAIN_DB_PREFIX."product";
        $sql .= " WHERE (barcode IS NULL OR barcode = '')";
        $sql .= " AND entity IN (".getEntity('product').")";
        $sql .= $db->order("datec", "ASC");
        $sql .= $db->plimit($maxperinit);
        
        dol_syslog("codeinit", LOG_DEBUG);
        $resql = $db->query($sql);
        
        if (!$resql) {
            throw new \Exception('Failed to query products');
        }
        
        $num = $db->num_rows($resql);
        $nbok = 0;
        $i = 0;
        
        while ($i < min($num, $maxperinit)) {
            $obj = $db->fetch_object($resql);
            if ($obj) {
                $productstatic->id = $obj->rowid;
                $productstatic->ref = $obj->ref;
                $productstatic->type = $obj->fk_product_type;
                $nextvalue = $modBarCodeProduct->getNextValue($productstatic, '');
                
                $result = $productstatic->setValueFrom('barcode', $nextvalue, '', null, 'text', '', $user, 'PRODUCT_MODIFY');
                
                if ($result > 0) {
                    $nbok++;
                }
            }
            $i++;
        }
        
        return $nbok;
    }
    
    /**
     * Generate barcodes for third parties
     *
     * @param object $modBarCodeThirdparty
     * @param \Societe $thirdpartystatic
     * @param int $maxperinit
     * @return int Number of records modified
     */
    private function generateThirdpartyBarcodes(object $modBarCodeThirdparty, \Societe $thirdpartystatic, int $maxperinit): int
    {
        global $db, $user;
        
        $sql = "SELECT rowid";
        $sql .= " FROM ".MAIN_DB_PREFIX."societe";
        $sql .= " WHERE (barcode IS NULL OR barcode = '')";
        $sql .= " AND entity IN (".getEntity('societe').")";
        $sql .= $db->order("datec", "ASC");
        $sql .= $db->plimit($maxperinit);
        
        dol_syslog("codeinit", LOG_DEBUG);
        $resql = $db->query($sql);
        
        if (!$resql) {
            throw new \Exception('Failed to query thirdparties');
        }
        
        $num = $db->num_rows($resql);
        $nbok = 0;
        $i = 0;
        
        while ($i < min($num, $maxperinit)) {
            $obj = $db->fetch_object($resql);
            if ($obj) {
                $thirdpartystatic->id = $obj->rowid;
                $nextvalue = $modBarCodeThirdparty->getNextValue($thirdpartystatic, '');
                
                $result = $thirdpartystatic->setValueFrom('barcode', $nextvalue, '', null, 'text', '', $user, 'THIRDPARTY_MODIFY');
                
                if ($result > 0) {
                    $nbok++;
                }
            }
            $i++;
        }
        
        return $nbok;
    }
}
