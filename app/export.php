<?php

$file_url = 'store/filters.json';
header('Content-Type: application/octet-stream');
// header('Content-Transfer-Encoding: Binary');
header('Content-disposition: attachment; filename="' . basename($file_url) . '"');
readfile($file_url);
