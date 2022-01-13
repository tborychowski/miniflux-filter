<?php

const BASE_PATH = 'store' . DIRECTORY_SEPARATOR;
const DAY = 86400;
const FEEDS_CACHE_TIMEOUT = DAY;       // 1 day
const ICON_CACHE_TIMEOUT = 30 * DAY;   // 1 month

function clear_cache ($name) {
	$fname = BASE_PATH . $name . '.json';
	if (file_exists($fname)) unlink($fname);
}


function saveToCache ($name, $json_data) {
	$fname = BASE_PATH . $name . '.json';
	$data = json_encode($json_data, JSON_PRETTY_PRINT);
	file_put_contents($fname, $data);
}


function getFromCache ($name) {
	$fname = BASE_PATH . $name . '.json';
	if (!file_exists($fname)) return [];

	if (time() - filemtime($fname) > FEEDS_CACHE_TIMEOUT) return [];

	$data = file_get_contents($fname);
	return json_decode($data, true);
}


function getIconFromCache ($id) {
	$pattern = BASE_PATH . "icon-$id.*";
	$fname = find_file($pattern);
	if (empty($fname)) return [];
	if (time() - filemtime($fname) > ICON_CACHE_TIMEOUT) return [];

	$icon = file_get_contents($fname);
	$ext = pathinfo($fname, PATHINFO_EXTENSION);
	$type = get_mime_from_extension($ext);
	return [ 'mime_type' => $type, 'icon' => $icon ];
}


function saveIconToCache ($id, $data) {
	$type = $data['mime_type'];
	$icon = file_get_contents('data://' . substr($data['data'], 5));
	$ext = get_extension_from_mime($type);
	$path = BASE_PATH . "icon-$id.$ext";
	file_put_contents($path, $icon);
	return [ 'mime_type' => $type, 'icon' => $icon ];
}


function get_extension_from_mime ($type = 'png') {
	if (stripos($type, 'svg')) return 'svg';
	if (stripos($type, 'jpg')) return 'jpg';
	if (stripos($type, 'jpeg')) return 'jpeg';
	if (stripos($type, 'ico')) return 'ico';
	return 'png';
}

function get_mime_from_extension ($ext = 'png') {
	if ($ext == 'svg') return 'image/svg+xml';
	if ($ext == 'jpg') return 'image/jpg';
	if ($ext == 'jpeg') return 'image/jpeg';
	if ($ext == 'ico') return 'image/x-icon';
	return 'image/png';
}


function find_file ($pattern) {
	if (empty($pattern)) return null;
	$found = glob($pattern);
	if (!empty($found) && count($found) > 0) return $found[0];
}
