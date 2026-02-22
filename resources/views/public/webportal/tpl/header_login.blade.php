{{-- Blade version of template --}}
@php
@php
/**
 * @var Context $context	Object Context for webportal
 */

// Protection to avoid direct call of template
if (empty($context) || !is_object($context)) {
	print "Error, template page can't be called as URL";
	exit(1);
}

global $langs;


// Return HTTP headers
top_httphead();

$jNotifyCSSUrl = dirname($context->rootUrl).'/public/includes/jquery/plugins/jnotify/jquery.jnotify.min.css?layout=classic';
$jQueryJSUrl = dirname($context->rootUrl).'/public/includes/jquery/js/jquery.min.js';
$jNotifyJSUrl = dirname($context->rootUrl).'/public/includes/jquery/plugins/jnotify/jquery.jnotify.min.js';

$bodyClass = [
	'login-page'
];

$loginFormTheme = getDolGlobalString('WEBPORTAL_LOGIN_FORM_THEME', 'default');
if (!empty($loginFormTheme)) {
	$loginFormTheme = mb_strtolower($loginFormTheme, 'UTF-8');
	// Replace spaces and consecutive whitespace with a single dash
	$loginFormTheme = preg_replace('/\s+/', '-', $loginFormTheme);
	// Remove all characters except letters, numbers, dash and underscore
	$loginFormTheme = preg_replace('/[^a-z0-9\-_]/', '', $loginFormTheme);
	// Remove leading or trailing dash/underscore
	$loginFormTheme = trim($loginFormTheme, '-_');

	$bodyClass[] = 'login-form-'.$loginFormTheme;
}

$langs->load("main", 0, 1);
$bodyClass[] = ($langs->trans("DIRECTION") == 'rtl' ? 'direction-rtl' : 'direction-ltr');

// TODO add HOOK here to allow customise headers add body class
@endphp
<!-- file header_login.blade.php -->
<!DOCTYPE html>
<html lang="{{ substr($langs->defaultlang, 0, 2) }}">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>
		{{ !empty($context->title) ? $context->title : 'WebPortal' }}
	</title>
	<link rel="stylesheet" href="{{ $context->rootUrl.'css/style.css.php' }}">
	<link rel="stylesheet" href="{{ $context->rootUrl.'css/themes/custom.css.php' }}">

	<link rel="stylesheet" href="{{ dirname($context->rootUrl).'/theme/common/fontawesome-5/css/all.min.css?layout=classic' }}">
	<link rel="stylesheet" href="{{ $jNotifyCSSUrl }} ">
	<script src="{{ $jQueryJSUrl }}"></script>
	<script src="{{ $jNotifyJSUrl }}"></script>
</head>
<body class="{{ dolPrintHTMLForAttribute(implode(' ', $bodyClass)) }}">
