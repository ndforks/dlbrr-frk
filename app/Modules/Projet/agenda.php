<?php
/* Copyright (C) 2005-2016 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2009 Regis Houssin        <regis.houssin@inodbox.com>
 * Copyright (C) 2024		MDW							<mdeweerd@users.noreply.github.com>
 * Copyright (C) 2024       Frédéric France         <frederic.france@free.fr>
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
 *      \file       htdocs/projet/agenda.php
 *      \ingroup    project
 *		\brief      Page with events on project
 */

// Load Dolibarr environment
require '../main.inc.php';
/**
 * @var Conf $conf
 * @var DoliDB $db
 * @var HookManager $hookmanager
 * @var Translate $langs
 * @var User $user
 */
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT.'/projet/class/project.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/project.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/company.lib.php';

// Load translation files required by the page
$langs->load("projects");

$id     = request()->integer('id', 0);
$ref    = request()->input('ref');
$socid  = request()->integer('socid', 0);
$action = request()->input('action');
$contextpage = request()->input('contextpage');

$limit = request()->integer('limit', 0) ? request()->integer('limit', 0) : $conf->liste_limit;
$sortfield = request()->input('sortfield');
$sortorder = request()->input('sortorder');
$page = request()->has('pageplusone') ? (request()->integer('pageplusone', 0) - 1) : request()->integer('page', 0);
$page = is_numeric($page) ? $page : 0;
$page = $page == -1 ? 0 : $page;
if (!$sortfield) {
	$sortfield = "a.datep,a.id";
}
if (!$sortorder) {
	$sortorder = "DESC";
}
$offset = $limit * $page;
$pageprev = $page - 1;
$pagenext = $page + 1;

if (is_array(request()->input('actioncode'))) {
	$actioncode = request()->input('actioncode', []);
	if (!count($actioncode)) {
		$actioncode = '0';
	}
} else {
	$actioncode = GETPOST("actioncode", "alpha", 3) ? GETPOST("actioncode", "alpha", 3) : (request()->input('actioncode') == '0' ? '0' : getDolGlobalString('AGENDA_DEFAULT_FILTER_TYPE_FOR_OBJECT'));
}

$search_rowid = request()->integer('search_rowid', 0);
$search_agenda_label = request()->input('search_agenda_label');
$search_complete = request()->input('search_complete');
$search_filtert = request()->integer('search_filtert', 0);
$search_dateevent_start = GETPOSTDATE('dateevent_start');
$search_dateevent_end = GETPOSTDATE('dateevent_end');

$hookmanager->initHooks(array('projectcardinfo'));

$object = new Project($db);

// Security check
$id = request()->integer('id', 0);
$socid = 0;
//if ($user->socid > 0) $socid = $user->socid;    // For external user, no check is done on company because readability is managed by public status of project and assignment.
restrictedArea($user, 'projet', $id, 'projet&project');

if (!$user->hasRight('projet', 'lire')) {
	abort(403);
}


/*
 * Actions
 */

$parameters = array('id' => $socid);
$reshook = $hookmanager->executeHooks('doActions', $parameters, $object, $action); // Note that $action and $object may have been modified by some hooks
if ($reshook < 0) {
	setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');
}

// Purge search criteria
if (request()->input('button_removefilter_x') || request()->input('button_removefilter.x') || request()->input('button_removefilter')) { // All tests are required to be compatible with all browsers
	$actioncode = '';
	$search_rowid = '';
	$search_agenda_label = '';
	$search_complete = '';
	$search_filtert = '';
}



/*
 * View
 */

$form = new Form($db);

if ($id > 0 || !empty($ref)) {
	$object->fetch($id, $ref);
	$object->fetch_thirdparty();
	if (getDolGlobalString('PROJECT_ALLOW_COMMENT_ON_PROJECT') && method_exists($object, 'fetchComments') && empty($object->comments)) {
		$object->fetchComments();
	}
	$object->info($object->id);
}
$agenda = (isModEnabled('agenda') && ($user->hasRight('agenda', 'myactions', 'read') || $user->hasRight('agenda', 'allactions', 'read'))) ? '/'.$langs->trans("Agenda") : '';
$title = $langs->trans('Events').$agenda.' - '.$object->ref.' '.$object->name;
if (getDolGlobalString('MAIN_HTML_TITLE') && preg_match('/projectnameonly/', getDolGlobalString('MAIN_HTML_TITLE')) && $object->name) {
	$title = $object->ref.' '.$object->name.' - '.$langs->trans("Info");
}
$help_url = "EN:Module_Projects|FR:Module_Projets|ES:M&oacute;dulo_Proyectos";
llxHeader("", $title, $help_url, '', 0, 0, '', '', '', 'mod-project page-card_agenda');

$head = project_prepare_head($object);

print dol_get_fiche_head($head, 'agenda', $langs->trans("Project"), -1, ($object->public ? 'projectpub' : 'project'));


// Project card

if (!empty($_SESSION['pageforbacktolist']) && !empty($_SESSION['pageforbacktolist']['project'])) {
	$tmpurl = $_SESSION['pageforbacktolist']['project'];
	$tmpurl = preg_replace('/__SOCID__/', (string) $object->socid, $tmpurl);
	$linkback = '<a href="'.$tmpurl.(preg_match('/\?/', $tmpurl) ? '&' : '?'). 'restore_lastsearch_values=1">'.$langs->trans("BackToList").'</a>';
} else {
	$linkback = '<a href="'.DOL_URL_ROOT.'/projet/list.php?restore_lastsearch_values=1">'.$langs->trans("BackToList").'</a>';
}

$morehtmlref = '<div class="refidno">';
// Title
$morehtmlref .= $object->title;
// Thirdparty
if (!empty($object->thirdparty->id) && $object->thirdparty->id > 0) {
	$morehtmlref .= '<br>'.$object->thirdparty->getNomUrl(1, 'project');
}
$morehtmlref .= '</div>';

// Define a complementary filter for search of next/prev ref.
if (!$user->hasRight('projet', 'all', 'lire')) {
	$objectsListId = $object->getProjectsAuthorizedForUser($user, 0, 0);
	$object->next_prev_filter = "rowid:IN:".$db->sanitize(count($objectsListId) ? implode(',', array_keys($objectsListId)) : '0');
}

dol_banner_tab($object, 'ref', $linkback, 1, 'ref', 'ref', $morehtmlref);


print '<div class="fichecenter">';
print '<div class="underbanner clearboth"></div>';

dol_print_object_info($object, 1);

print '</div>';

print '<div class="clearboth"></div>';

print dol_get_fiche_end();


// Actions buttons

$out = '';
$permok = $user->hasRight('agenda', 'myactions', 'create');
if ($permok) {
	$out .= '&projectid='.$object->id;
}



//print '</div>';

if (!empty($object->id)) {
	print '<br>';

	//print '<div class="tabsAction">';
	$morehtmlright = '';

	// Show link to change view in message
	$messagingUrl = DOL_URL_ROOT.'/projet/messaging.php?id='.$object->id;
	$morehtmlright .= dolGetButtonTitle($langs->trans('ShowAsConversation'), '', 'fa fa-comments imgforviewmode', $messagingUrl, '', 1);

	// Show link to change view in agenda
	$messagingUrl = DOL_URL_ROOT.'/projet/agenda.php?id='.$object->id;
	$morehtmlright .= dolGetButtonTitle($langs->trans('MessageListViewType'), '', 'fa fa-bars imgforviewmode', $messagingUrl, '', 2);


	// // Show link to send an email (if read and not closed)
	// $btnstatus = $object->status < Ticket::STATUS_CLOSED && $action != "presend" && $action != "presend_addmessage";
	// $url = 'card.php?track_id='.$object->track_id.'&action=presend_addmessage&mode=init&private_message=0&send_email=1&backtopage='.urlencode($_SERVER["PHP_SELF"].'?track_id='.$object->track_id).'#formmailbeforetitle';
	// $morehtmlright .= dolGetButtonTitle($langs->trans('SendMail'), '', 'fa fa-paper-plane', $url, 'email-title-button', $btnstatus);

	// // Show link to add a private message (if read and not closed)
	// $btnstatus = $object->status < Ticket::STATUS_CLOSED && $action != "presend" && $action != "presend_addmessage";
	// $url = 'card.php?track_id='.$object->track_id.'&action=presend_addmessage&mode=init&backtopage='.urlencode($_SERVER["PHP_SELF"].'?track_id='.$object->track_id).'#formmailbeforetitle';
	// $morehtmlright .= dolGetButtonTitle($langs->trans('TicketAddMessage'), '', 'fa fa-comment-dots', $url, 'add-new-ticket-title-button', $btnstatus);

	// Show link to add event
	if (isModEnabled('agenda')) {
		$addActionBtnRight = $user->hasRight('agenda', 'myactions', 'create') || $user->hasRight('agenda', 'allactions', 'create');
		$morehtmlright .= dolGetButtonTitle($langs->trans('AddAction'), '', 'fa fa-plus-circle', DOL_URL_ROOT.'/comm/action/card.php?action=create'.$out.'&socid='.$object->socid.'&backtopage='.urlencode($_SERVER["PHP_SELF"].'?id='.$object->id), '', (int) $addActionBtnRight);
	}

	$param = '&id='.$object->id;
	if (!empty($contextpage) && $contextpage != $_SERVER["PHP_SELF"]) {
		$param .= '&contextpage='.urlencode($contextpage);
	}
	if ($limit > 0 && $limit != $conf->liste_limit) {
		$param .= '&limit='.((int) $limit);
	}
	if ($search_rowid) {
		$param .= '&search_rowid='.urlencode($search_rowid);
	}
	if ($actioncode !== '' && $actioncode !== '-1') {
		$param .= '&actioncode='.urlencode($actioncode);
	}
	if ($search_agenda_label) {
		$param .= '&search_agenda_label='.urlencode($search_agenda_label);
	}
	if ($search_complete != '') {
		$param .= '&search_complete='.urlencode($search_complete);
	}
	if ($search_filtert != '') {
		$param .= '&search_filtert='.urlencode((string) $search_filtert);
	}
	if ($search_dateevent_start != '') {
		$param .= '&dateevent_startyear='.request()->integer('dateevent_startyear', 0);
		$param .= '&dateevent_startmonth='.request()->integer('dateevent_startmonth', 0);
		$param .= '&dateevent_startday='.request()->integer('dateevent_startday', 0);
	}
	if ($search_dateevent_end != '') {
		$param .= '&dateevent_endyear='.request()->integer('dateevent_endyear', 0);
		$param .= '&dateevent_endmonth='.request()->integer('dateevent_endmonth', 0);
		$param .= '&dateevent_endday='.request()->integer('dateevent_endday', 0);
	}

	// Try to know count of actioncomm from cache
	require_once DOL_DOCUMENT_ROOT.'/core/lib/memory.lib.php';
	$cachekey = 'count_events_project_'.$object->id;
	$nbEvent = dol_getcache($cachekey);

	$titlelist = $langs->trans("ActionsOnProject").(is_numeric($nbEvent) ? '<span class="opacitymedium colorblack paddingleft">('.$nbEvent.')</span>' : '');
	if (!empty($conf->dol_optimize_smallscreen)) {
		$titlelist = $langs->trans("Actions").(is_numeric($nbEvent) ? '<span class="opacitymedium colorblack paddingleft">('.$nbEvent.')</span>' : '');
	}

	print_barre_liste($titlelist, 0, $_SERVER["PHP_SELF"], $param, $sortfield, $sortorder, '', 0, -1, '', 0, $morehtmlright, '', 0, 1, 0);

	// List of all actions
	$filters = array();
	$filters['search_agenda_label'] = $search_agenda_label;
	$filters['search_rowid'] = $search_rowid;
	$filters['search_complete'] = $search_complete;		// Can be 'na', '0', '100', '50'
	$filters['search_filtert'] = $search_filtert;

	// TODO Replace this with the same code than into list.php
	show_actions_done($conf, $langs, $db, $object, null, 0, $actioncode, '', $filters, $sortfield, $sortorder);
}

// End of page
llxFooter();
$db->close();
