<?php
const LOGGED_IN = 'logged_in';
const COOKIE_NAME = 'auth';
const DEFAULT_PATH = 'filters';

define('ADMIN_PASSWORD', getenv('ADMIN_PASSWORD'));

function go ($path) { return header('Location: ' . $path); }
function goHome () { return go('./'); }
function goToLogin() { return go('login'); }
function goToSettings() { return go('settings'); }


function tryToLogin () {
	if (!ADMIN_PASSWORD) {
		$_SESSION[LOGGED_IN] = true;
		clear_auth_cookie();
		if (getPath() == 'login') goHome();
		return;
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password'])) {
		$pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
	}
	elseif (isset($_COOKIE[COOKIE_NAME])) $pass = $_COOKIE[COOKIE_NAME];

	if (isset($pass)) {
		if (!password_verify(ADMIN_PASSWORD, $pass)) return goToLogin();

		$_SESSION[LOGGED_IN] = true;
		save_admin_cookie ($pass);

		goHome();
	}
}


function logout () {
	$_SESSION[LOGGED_IN] = false;
	clear_auth_cookie();
	session_unset();
	goToLogin();
}

function save_admin_cookie ($val) {
	$month = time() + 30 * 24 * 3600;
	setcookie(COOKIE_NAME, $val, $month, '/', $_SERVER['hostname']);
}

function clear_auth_cookie () {
	setcookie(COOKIE_NAME, null, 1, '/', $_SERVER['hostname']);
}

function requiresAuth() {
	$is_logged_in = isset($_SESSION[LOGGED_IN]) && $_SESSION[LOGGED_IN] == true;
	$path = getPath();
	if (!$is_logged_in && $path != 'login') return goToLogin();
}


function getPath ($withDefault = false) {
	$path = isset($_SERVER['PATH_INFO']) ? substr($_SERVER['PATH_INFO'], 1) : '';
	if ($withDefault && empty($path)) $path = DEFAULT_PATH;

	return $path;
}
