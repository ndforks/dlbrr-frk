<?php
/* Copyright (C) 2009-2010  Laurent Destailleur     <eldy@users.sourceforge.net>
 * Copyright (C) 2024-2025  Frédéric France         <frederic.france@free.fr>
 * Copyright (C) 2024-2025	MDW						<mdeweerd@users.noreply.github.com>
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

namespace App\Http\Controllers\Asterisk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller for Asterisk click-to-dial wrapper
 * 
 * This controller handles the click-to-dial functionality
 * connecting to an Asterisk server to initiate calls.
 */
class WrapperController extends Controller
{
    /**
     * Handle Asterisk click-to-dial request
     *
     * @param Request $request
     * @return View
     */
    public function __invoke(Request $request): View
    {
        global $db, $conf;
        
        // Security check
        if (!isModEnabled('clicktodial')) {
            abort(403);
        }
        
        // Get and sanitize input parameters
        $login = preg_replace('/[\n\r]/', '', $request->input('login', ''));
        $password = preg_replace('/[\n\r]/', '', $request->input('password', ''));
        $caller = preg_replace('/[\n\r]/', '', $request->input('caller', ''));
        $called = preg_replace('/[\n\r]/', '', $request->input('called', ''));
        
        // Define Asterisk setup
        if (getDolGlobalString('ASTERISK_INDICATIF') == 'NONE') {
            $conf->global->ASTERISK_INDICATIF = '';
        }
        
        // Asterisk configuration
        $strHost = getDolGlobalString('ASTERISK_HOST', '127.0.0.1');
        $channel = getDolGlobalString('ASTERISK_TYPE', 'SIP/');
        $prefix = getDolGlobalString('ASTERISK_INDICATIF', '0');
        $port = getDolGlobalInt('ASTERISK_PORT', 5038);
        $strContext = getDolGlobalString('ASTERISK_CONTEXT', 'from-internal');
        $strWaitTime = getDolGlobalString('ASTERISK_WAIT_TIME', '30');
        $strPriority = getDolGlobalString('ASTERISK_PRIORITY', '1');
        $strMaxRetry = getDolGlobalString('ASTERISK_MAX_RETRY', "2");
        
        $error = null;
        $found = null;
        $dialStatus = null;
        
        if (empty($called)) {
            $error = 'Bad parameters in URL. Must be ' . htmlspecialchars($_SERVER['PHP_SELF']) . 
                     '?caller=99999&called=99999&login=xxxxx&password=xxxxx';
        } else {
            // Look up the called party in the database
            $sql = "SELECT s.nom as name FROM " . MAIN_DB_PREFIX . "societe as s";
            $sql .= " LEFT JOIN " . MAIN_DB_PREFIX . "socpeople as sp ON sp.fk_soc = s.rowid";
            $sql .= " WHERE s.entity IN (" . getEntity('societe') . ")";
            $sql .= " AND (s.phone='" . $db->escape($called) . "'";
            $sql .= " OR sp.phone='" . $db->escape($called) . "'";
            $sql .= " OR sp.phone_perso='" . $db->escape($called) . "'";
            $sql .= " OR sp.phone_mobile='" . $db->escape($called) . "')";
            $sql .= $db->plimit(1);
            
            dol_syslog('click to dial search information with phone ' . $called, LOG_DEBUG);
            $resql = $db->query($sql);
            
            if ($resql) {
                $obj = $db->fetch_object($resql);
                if ($obj) {
                    $found = $obj->name;
                } else {
                    $found = 'Not found';
                }
                $db->free($resql);
            } else {
                abort(500, 'Database Error');
            }
            
            // Attempt to connect to Asterisk
            $number = strtolower($called);
            $pos = strpos($number, "local");
            
            if ($pos === false) {
                $errno = 0;
                $errstr = '';
                $strCallerId = "Dolibarr caller $found <" . strtolower($number) . ">";
                $oSocket = @fsockopen($strHost, (int) $port, $errno, $errstr, 10);
                
                if (!$oSocket) {
                    $error = "Failed to execute fsockopen($strHost, $port, \$errno, \$errstr, 10)<br>\n";
                    $error .= $errstr . " (" . $errno . ")<br>\n";
                    dol_syslog($error, LOG_ERR);
                } else {
                    $txt = "Call Asterisk dialer for caller: " . $caller . ", called: " . $called . " clicktodiallogin: " . $login;
                    dol_syslog($txt);
                    
                    // Send commands to Asterisk
                    fwrite($oSocket, "Action: login\r\n");
                    fwrite($oSocket, "Events: off\r\n");
                    fwrite($oSocket, "Username: $login\r\n");
                    fwrite($oSocket, "Secret: $password\r\n\r\n");
                    fwrite($oSocket, "Action: originate\r\n");
                    fwrite($oSocket, "Channel: " . $channel . $caller . "\r\n");
                    fwrite($oSocket, "WaitTime: $strWaitTime\r\n");
                    fwrite($oSocket, "CallerId: $strCallerId\r\n");
                    fwrite($oSocket, "Exten: " . $prefix . $number . "\r\n");
                    fwrite($oSocket, "Context: $strContext\r\n");
                    fwrite($oSocket, "Priority: $strPriority\r\n\r\n");
                    fwrite($oSocket, "Action: Logoff\r\n\r\n");
                    sleep(2);
                    fclose($oSocket);
                    
                    $dialStatus = 'success';
                }
            }
        }
        
        return view('asterisk.wrapper', [
            'error' => $error,
            'found' => $found,
            'dialStatus' => $dialStatus,
            'caller' => $caller,
            'called' => $called,
        ]);
    }
}
