<?php
const DEFAULTS = [
	'url' => '',
	'token' => '',
	'refresh_freq' => '5',
];
const SETTINGS_FNAME = 'store/settings.json';

function isSettingsSet () {
	$s = $_SESSION['settings'] ?? null;
	if (!$s) return false;
	if (empty($s['token'])) return false;
	if (empty($s['url'])) return false;
	return true;
}

function saveSettings () {
	$_SESSION['settings'] = array_merge(DEFAULTS, $_POST);
	$json_data = json_encode($_SESSION['settings'], JSON_PRETTY_PRINT);
	file_put_contents(SETTINGS_FNAME, $json_data);
}


function getSettings () {
	if (!isSettingsSet()) {
		if (file_exists(SETTINGS_FNAME)) {
			$data = file_get_contents(SETTINGS_FNAME);
			$_SESSION['settings'] =  json_decode($data, true);
		}
	}
	return $_SESSION['settings'] ?? DEFAULTS;
}
