<?php

session_start();
date_default_timezone_set('Europe/Dublin');
require_once 'lib/actions.php';
require_once 'lib/miniflux.php';
require_once 'lib/settings.php';

requiresAuth();

if (!isset($_GET['id'])) die('#');

$settings = getSettings();
if (!isSettingsSet()) die('#');

$m = new Miniflux($settings);
$res = $m->getFeedIcon($_GET['id']);
$type = $res['mime_type'];

header("Content-type: $type");
echo $res['icon'];
