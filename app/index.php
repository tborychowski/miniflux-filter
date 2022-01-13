<?php
ob_start();
session_start();
date_default_timezone_set('Europe/Dublin');
require_once 'lib/actions.php';

requiresAuth();

if (getPath() == 'logout') return logout();

require('inc/header.php');
require('inc/nav.php');
echo '<main>';
require('content/' . getPath(true) . '.php');
echo '</main>';
require('inc/footer.php');
ob_end_flush();
