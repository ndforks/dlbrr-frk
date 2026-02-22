<?php
/* Copyright (C) 2006-2014	Laurent Destailleur	<eldy@users.sourceforge.net>
 * Copyright (C) 2011		Juanjo Menent		<jmenent@2byte.es>
 * Copyright (C) 2015		Raphaël Doursenaud	<rdoursenaud@gpcsolutions.fr>
 * Copyright (C) 2021		Regis Houssin		<regis.houssin@inodbox.com>
 * Copyright (C) 2024		Frédéric France		<frederic.france@free.fr>
 * Copyright (C) 2025		MDW					<mdeweerd@users.noreply.github.com>
 * Copyright (C) 2025		Anthony Berton		<anthony.berton@bb2a.fr>
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
 *		\file 		htdocs/admin/tools/export.php
 *		\brief      Page to export a database into a dump file
 */

// Load Dolibarr environment
require '../../main.inc.php';
/**
 * @var Conf $conf
 * @var DoliDB $db
 * @var HookManager $hookmanager
 * @var Translate $langs
 * @var User $user
 *
 * @var string	$dolibarr_main_restrict_os_commands
 */
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/utils.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';

$langs->load("admin");

$action = request()->input('action');
$what = request()->input('what');
$export_type = request()->input('export_type');
$file = dol_sanitizeFileName(request()->input('filename_template'));

// Load variable for pagination
$limit = request()->integer('limit', 0) ? request()->integer('limit', 0) : $conf->liste_limit;
$sortfield = request()->input('sortfield');
$sortorder = request()->input('sortorder');
$page = request()->has('pageplusone') ? (request()->integer('pageplusone', 0) - 1) : request()->integer('page', 0);
if (empty($page) || $page == -1 || request()->input('button_search') || request()->input('button_removefilter') || (empty($toselect) && $massaction === '0')) {
	$page = 0;
}     // If $page is not defined, or '' or -1 or if we click on clear filters or if we select empty mass action
$offset = $limit * $page;
if (!$sortorder) {
	$sortorder = "DESC";
}
if (!$sortfield) {
	$sortfield = "date";
}

if (!$user->admin) {
	abort(403);
}

$errormsg = '';

$utils = new Utils($db);


/*
 * Actions
 */

if ($file && !$what) {
	//print DOL_URL_ROOT.'/dolibarr_export.php';
	header("Location: ".DOL_URL_ROOT.'/admin/tools/dolibarr_export.php?msg='.urlencode($langs->trans("ErrorFieldRequired", $langs->transnoentities("ExportMethod"))).(request()->integer('page_y', 0) ? '&page_y='.request()->integer('page_y', 0) : ''));
	exit;
}

if ($action == 'delete') {
	$file = $conf->admin->dir_output.'/'.dol_sanitizeFileName(request()->input('urlfile'));
	$ret = dol_delete_file($file, 1);
	if ($ret) {
		setEventMessages($langs->trans("FileWasRemoved", request()->input('urlfile')), null, 'mesgs');
	} else {
		setEventMessages($langs->trans("ErrorFailToDeleteFile", request()->input('urlfile')), null, 'errors');
	}
	$action = '';
}

$_SESSION["commandbackuplastdone"] = '';
$_SESSION["commandbackuptorun"] = '';
$_SESSION["commandbackupresult"] = '';

// Increase limit of time. Works only if we are not in safe mode
$ExecTimeLimit = 600; // Set it to 0 to not use a forced time limit
if (!empty($ExecTimeLimit)) {
	$err = error_reporting();
	error_reporting(0); // Disable all errors
	//error_reporting(E_ALL);
	@set_time_limit($ExecTimeLimit); // Need more than 240 on Windows 7/64
	error_reporting($err);
}
$MemoryLimit = 0;
if (!empty($MemoryLimit)) {
	@ini_set('memory_limit', $MemoryLimit);
}

// We will send fake headers to avoid browser timeout when buffering
$time_start = time();


$outputdir  = $conf->admin->dir_output.'/backup';
$result = dol_mkdir($outputdir);


$lowmemorydump = (int) (request()->has('lowmemorydump') ? request()->integer('lowmemorydump', 0) : getDolGlobalInt('MAIN_LOW_MEMORY_DUMP'));


// MYSQL
if ($what == 'mysql') {
	$cmddump = request()->input('mysqldump'); // Do not sanitize here with 'alpha', will be sanitize later by dol_sanitizePathName and escapeshellarg
	$cmddump = dol_sanitizePathName($cmddump);
	$basenamecmddump = basename(str_replace('\\', '/', $cmddump));

	// Add a fallback when we detect something wrong with the path of the dump command
	if (preg_match('/\//', str_replace('\\', '/', $cmddump))) {			// If command is a full path
		if (!dol_is_file($cmddump)) {									// And if file not reachable with its full path
			$reg = array();
			if (preg_match('/mysqldump(\.exe)?$/', $cmddump, $reg)) {	// And if command ends with mysqldump
				$cmddump = 'mysqldump'.(empty($reg[1]) ? '' : $reg[1]);	// Then we try the command with no forced path
			}
		}
	}

	if (!empty($dolibarr_main_restrict_os_commands)) {
		$arrayofallowedcommand = explode(',', $dolibarr_main_restrict_os_commands);
		$arrayofallowedcommand = array_map('trim', $arrayofallowedcommand);
		dol_syslog("Command are restricted to ".$dolibarr_main_restrict_os_commands.". We check that one of this command is inside ".$cmddump);
		if (!in_array($basenamecmddump, $arrayofallowedcommand)) {	// the provided command $cmddump must be an allowed command
			$langs->load("errors");
			$errormsg = $langs->trans('CommandIsNotInsideAllowedCommands');
			$errormsg .= '<br>'.$langs->trans('ErrorCheckTheCommandInsideTheAdvancedOptions');
		}
	}

	if (!$errormsg && $cmddump) {
		dolibarr_set_const($db, 'SYSTEMTOOLS_MYSQLDUMP', $cmddump, 'chaine', 0, '', 0);
	}

	if (!$errormsg) {
		$result = $utils->dumpDatabase(request()->input('compression'), $what, 0, $file, 0, 0, $lowmemorydump);

		$errormsg = $utils->error;
		$_SESSION["commandbackuplastdone"] = $utils->result['commandbackuplastdone'];
		$_SESSION["commandbackuptorun"] = $utils->result['commandbackuptorun'];
	}
}

// MYSQL NO BIN
if ($what == 'mysqlnobin') {
	$utils->dumpDatabase(request()->input('compression'), $what, 0, $file, 0, 0, $lowmemorydump);

	$errormsg = $utils->error;
	$_SESSION["commandbackuplastdone"] = $utils->result['commandbackuplastdone'];
	$_SESSION["commandbackuptorun"] = $utils->result['commandbackuptorun'];
}

// POSTGRESQL
if ($what == 'postgresql') {
	$cmddump = request()->input('postgresqldump'); // Do not sanitize here with 'alpha', will be sanitize later by dol_sanitizePathName and escapeshellarg
	$cmddump = dol_sanitizePathName($cmddump);

	/* Not required, the command is output on screen but not ran for pgsql
	if (!empty($dolibarr_main_restrict_os_commands))
	{
		$arrayofallowedcommand=explode(',', $dolibarr_main_restrict_os_commands);
		$arrayofallowedcommand = array_map('trim', $arrayofallowedcommand);
		dol_syslog("Command are restricted to ".$dolibarr_main_restrict_os_commands.". We check that one of this command is inside ".$cmddump);
		$basenamecmddump = basename(str_replace('\\', '/', $cmddump));
		if (! in_array($basenamecmddump, $arrayofallowedcommand))	// the provided command $cmddump must be an allowed command
		{
			$errormsg=$langs->trans('CommandIsNotInsideAllowedCommands');
		}
	} */

	if (!$errormsg && $cmddump) {
		dolibarr_set_const($db, 'SYSTEMTOOLS_POSTGRESQLDUMP', $cmddump, 'chaine', 0, '', 0);
	}

	if (!$errormsg) {
		$utils->dumpDatabase(request()->input('compression'), $what, 0, $file, 0, 0, $lowmemorydump);
		$errormsg = $utils->error;
		$_SESSION["commandbackuplastdone"] = $utils->result['commandbackuplastdone'];
		$_SESSION["commandbackuptorun"] = $utils->result['commandbackuptorun'];
	}

	$what = ''; // Clear to show message to run command
}


if ($errormsg) {
	setEventMessages($langs->trans("Error")." : ".$errormsg, null, 'errors');

	$resultstring = '';
	$resultstring .= '<div class="error">'.$langs->trans("Error")." : ".$errormsg.'</div>';

	$_SESSION["commandbackupresult"] = $resultstring;
} else {
	if ($what) {
		setEventMessages($langs->trans("BackupFileSuccessfullyCreated").'.<br>'.$langs->trans("YouCanDownloadBackupFile"), null, 'mesgs');

		$resultstring = '<div class="ok">';
		$resultstring .= $langs->trans("BackupFileSuccessfullyCreated").'.<br>';
		$resultstring .= $langs->trans("YouCanDownloadBackupFile");
		$resultstring .= '</div>';

		$_SESSION["commandbackupresult"] = $resultstring;
	}
	/*else
	{
		setEventMessages($langs->trans("YouMustRunCommandFromCommandLineAfterLoginToUser",$dolibarr_main_db_user,$dolibarr_main_db_user), null, 'warnings');
	}*/
}



/*
 * View
 */

top_httphead();

$db->close();

// Redirect to backup page
header("Location: dolibarr_export.php".(request()->integer('page_y', 0) ? '?page_y='.request()->integer('page_y', 0) : ''));
exit();
