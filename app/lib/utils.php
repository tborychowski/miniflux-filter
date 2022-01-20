<?php

function is_run_in_background () {
	return count(array_intersect($_SERVER['argv'], ['-b', '--background'])) > 0;
}

function is_cli () {
	return php_sapi_name() == 'cli';
}

function allow_cli_only ($msg = '') {
	if (is_cli()) {
		echo '<br>';
		echo $msg;
		echo '<br><br><a href="#" onclick="history.back()">&laquo; Go back</a> or <a href="./">Go home</a>';
		exit(0);
	}
}


function fetch ($method, $url, $body, $headers = []) {
	$context = stream_context_create([
		"http" => [
			"method" => $method,
			"header" => implode("\r\n", $headers),
			"content" => json_encode($body),
			"ignore_errors" => true,
		],
	]);
	$response = file_get_contents($url, false, $context);

	// $http_response_header materializes out of thin air
	$status_line = $http_response_header[0];
	preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $match);
	$status = $match[1];

	return [ 'status' => $status, 'text' => $response, 'json' => json_decode($response, true) ];
}


function get_item_by_id ($arr, $id) {
	$idx = get_idx_from_id($arr, $id);
	return $arr[$idx] ?? [];
}

function get_idx_from_id ($arr, $id) {
	return array_search($id, array_column($arr, 'id'));
}


function time_ago ($datetime, $full = false) {
	$now = new DateTime;
	$ago = new DateTime($datetime);
	$diff = $now->diff($ago);

	$diff->w = floor($diff->d / 7);
	$diff->d -= $diff->w * 7;

	$string = [
		'y' => 'year',
		'm' => 'month',
		'w' => 'week',
		'd' => 'day',
		'h' => 'hour',
		'i' => 'minute',
		's' => 'second',
	];

	foreach ($string as $k => &$v) {
		if ($diff->$k) $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
		else unset($string[$k]);
	}

	if (!$full) $string = array_slice($string, 0, 1);
	return $string ? implode(', ', $string) . ' ago' : 'just now';
}
