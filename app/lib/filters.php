<?php
require_once 'lib/utils.php';
require_once 'lib/logger.php';

const FILTERS_FNAME = 'store/filters.json';

function import_filters ($imported_filters) {
	foreach ($imported_filters as $idx => $filter) {
		$imported_filters[$idx]['id'] = uniqid();
		$imported_filters[$idx]['updated'] = date("Y-m-d H:i:s");
		$imported_filters[$idx]['filtered_count'] = 0;
	}
	$existing_filters = getFilters();
	$filters = array_merge($existing_filters, $imported_filters);
	saveFilters($filters);
}

function addFilter ($filters, $postData, $feeds) {
	$filter = [
		'id' => uniqid(),
		'created' => date("Y-m-d H:i:s"),
		'updated' => date("Y-m-d H:i:s"),
		'filtered_count' => 0
	];
	if (!empty($postData['feed'])) {
		$feedId = $postData['feed'];
		$feedIndex = get_idx_from_id($feeds, $feedId);
		$filter['feed_id'] = $feedId;
		$filter['feed_title'] = $feeds[$feedIndex]['title'];
	}
	if (!empty($postData['field'])) $filter['field'] = $postData['field'];
	if (!empty($postData['match'])) $filter['match'] = $postData['match'];

	if (!empty($filter) && !empty($filter['match'])) {
		$filters[] = $filter;
		saveFilters($filters);
	}
}


function updateFilter ($filters, $postData, $feeds, $id) {
	$idx = get_idx_from_id($filters, $id);
	$was_changed = false;
	if (!empty($postData['feed'])) {
		$feedId = $postData['feed'];
		$feedIndex = get_idx_from_id($feeds, $feedId);
		if ($filters[$idx]['feed_id'] != $feedId) {
			$filters[$idx]['feed_id'] = $feedId;
			$filters[$idx]['feed_title'] = $feeds[$feedIndex]['title'];
			$filters[$idx]['filtered_count'] = 0;
			$filters[$idx]['updated'] = date("Y-m-d H:i:s");
			$was_changed = true;
		}
	}
	if (!empty($postData['field']) && $filters[$idx]['field'] != $postData['field']) {
		$filters[$idx]['field'] = $postData['field'];
		$filters[$idx]['filtered_count'] = 0;
		$filters[$idx]['updated'] = date("Y-m-d H:i:s");
		$was_changed = true;
	}
	if (!empty($postData['match']) && $filters[$idx]['match'] != $postData['match']) {
		$filters[$idx]['match'] = $postData['match'];
		$filters[$idx]['filtered_count'] = 0;
		$filters[$idx]['updated'] = date("Y-m-d H:i:s");
		$was_changed = true;
	}
	if ($was_changed) saveFilters($filters);
	return $was_changed;
}


function deleteFilter ($filters, $id) {
	$idx = get_idx_from_id($filters, $id);
	unset($filters[$idx]);
	$filters = array_values($filters);
	saveFilters($filters);
}


function saveFilters ($filters) {
	file_put_contents(FILTERS_FNAME, json_encode($filters, JSON_PRETTY_PRINT));
}


function getFilters () {
	if (!file_exists(FILTERS_FNAME)) return [];

	$data = file_get_contents(FILTERS_FNAME);
	return json_decode($data, true);
}


function filter_count_inc ($filterId) {
	$filters = getFilters();
	$idx = get_idx_from_id($filters, $filterId);
	$filters[$idx]['filtered_count'] += 1;
	saveFilters($filters);
}

function should_filter_out_article ($filters, $article) {
	$article_filters = array_filter($filters, fn($f) => $f['feed_id'] == $article['feed_id']);
	if (empty($article_filters)) return false;

	$logger = new Logger();
	foreach ($article_filters as $filter) {
		$field = $filter['field'];
		$match = strtolower($filter['match']);
		$text = strtolower($article[$field]);

		if (strpos($text, $match) !== false) {
			filter_count_inc($filter['id']);
			$logger->success('Found match: filter ID: "' . $filter['id'] . '", article: "' . $article['title'] . '"');
			return true;
		}
	}

	return false;
}
