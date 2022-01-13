<?php
	require_once 'filters-menu.php';
	require_once 'lib/filters.php';

	const TEN_MB = 10485760;

	function error ($msg = 'Unknown error. Please try again.') {
		$_SESSION['error'] = $msg;
		header('Location: filters-import');
	}

	function success ($msg = '') {
		$_SESSION['success'] = $msg;
		header('Location: filters');
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$file = $_FILES['file'];
		$type = $_REQUEST['import_type'];

		if ($file['size'] > TEN_MB) return error('File too big. Maksimum size is ~10MB.');

		$uploaded_file_contents = file_get_contents($file['tmp_name']);
		if (isset($uploaded_file_contents)) {
			$json = json_decode($uploaded_file_contents, true);
			unlink($file['tmp_name']);
		}

		if (!isset($json)) return error('File upload problem. Please try again later.');

		if ($type == 'merge') import_filters($json);
		else saveFilters($json);

		success(count($json) . ' filters imported successfully!');
	}
?>


<form enctype="multipart/form-data" method="POST">
	<label>Import type:</label>
	<label><input type="radio" name="import_type" value="merge" checked> Append</label>
	<label><input type="radio" name="import_type" value="replace"> Replace</label>
	<br>
	<label for="file">Select a json file</label>
	<input id="file" type="file" name="file">
	<br>
	<br>

	<div class="buttons">
		<button type="submit" class="button button-primary">Upload</button>
		or
		<a href="filters">Cancel</a>
	</div>
</form>
