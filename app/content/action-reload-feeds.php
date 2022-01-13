<?php
require_once 'lib/cache.php';

clear_cache('feeds');

header('location: ' . $_SERVER['HTTP_REFERER']);
