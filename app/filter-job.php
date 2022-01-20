<?php

require_once 'lib/utils.php';
require_once 'lib/logger.php';


//*** FILTERING ************************************************************************************
require_once 'lib/filters.php';
require_once 'lib/miniflux.php';
require_once 'lib/settings.php';
$settings = getSettings();

$logger = new Logger();

// cron runs this script every minute
// this decides if the current run matches the frequency defined in settings
if (is_cli() && !is_run_in_background()) {
	$freq = intval($settings['refresh_freq']);
	$now = round(strtotime('now') / 60);
	if ($now % $freq != 0) return;
}

$logger->touch();

$filters = getFilters();
if (empty($filters)) {
	$logger->warning('You have not defined any filters.');
	exit(0);
}

$m = new Miniflux($settings);
$unread = $m->getUnread();
if (empty($unread)) {
	$logger->info('There are no unread articles to filter.');
	exit(0);
}

$logger->debug('Checking filters...');

$ids_to_filter = [];
foreach ($unread as $article) {
	if (should_filter_out_article($filters, $article)) $ids_to_filter[] = $article['id'];
}

$count = $m->markAsRead($ids_to_filter);
if ($count > 0) $logger->success('Marked ' . $count . ' article' . ($count > 1 ? 's' : '') . ' as read.');

$logger->debug('Checking filters...finished!');
