<?php
	$id = $_REQUEST['id'] ?? null;
?>
<section class="page-header">
	<h1>Filters</h1>
	<ul>
		<li><a href="filters">Filters</a></li>
		<!-- <li><a href="action-filters-run">Run in background</a></li> -->
		<li><a href="#"
			data-confirm="true"
			data-action="remove-feed"
			data-label-question="Are you sure?"
			data-label-yes="yes"
			data-label-no="no"
			data-label-loading="In progress..."
			data-url="filters-remove?id=<?= $id ?>"
			data-redirect-url="filters"
			style="display: inline;">Remove this filter</a>
		</li>
		<li><a href="action-reload-feeds">Reload feeds</a></li>
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
