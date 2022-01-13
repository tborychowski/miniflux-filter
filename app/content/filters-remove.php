<?php
	require_once 'lib/filters.php';


	if (isset($_REQUEST['id'])) {
		$filters = getFilters();
		deleteFilter($filters, $_REQUEST['id']);
		header('Location: filters');
	}
?>
