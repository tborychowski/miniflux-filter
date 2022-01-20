<section class="page-header">
	<h1>Filters</h1>
	<ul>
		<li><a href="filters">Filters</a></li>
		<li><a href="filters-add">Add Filter</a></li>
		<li><a href="export.php">Export</a></li>
		<li><a href="filters-import">Import</a></li>
		<li><a href="action-reload-feeds">Reload feeds</a></li>
		<li><a href="filter-job.php">Run all filters in background</a></li>
	</ul>
</section>

<?php

	if (!empty($_SESSION['success'])) {
		$success = $_SESSION['success'];
		unset($_SESSION['success']);
	}
	if (!empty($_SESSION['error'])) {
		$error = $_SESSION['error'];
		unset($_SESSION['error']);
	}

	if (isset($error)) echo '<div class="alert alert-error">' . $error . '</div>';
	if (isset($success)) echo '<div class="alert alert-success">' . $success . '</div>';
