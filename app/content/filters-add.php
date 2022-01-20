<?php
	require_once 'lib/actions.php';
	require_once 'lib/miniflux.php';
	require_once 'lib/settings.php';
	require_once 'lib/filters.php';

	$filters = getFilters();
	$settings = getSettings();
	if (!isSettingsSet()) return goToSettings();

	$m = new Miniflux($settings);
	$feeds = $m->getFeedTitles();

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		addFilter($filters, $_POST, $feeds);
		header('Location: action-filters-run');
	}

	require_once 'filters-menu.php';

?>

<form method="POST">
	<label for="feed">Feed</label>
	<select id="feed" name="feed">
		<?php
			foreach ($feeds as $feed) {
				$id = $feed['id'];
				$title = $feed['title'];
				echo "<option value=\"$id\">$title</option>";
			}
			?>
	</select>

	<label for="field">Field</label>
	<select id="field" name="field">
		<option value="title">Title</option>
		<option value="content">Content</option>
	</select>

	<label for="match">Match</label>
	<input id="match" type="text" name="match" placeholder="e.g. Sponsored">

	<div class="buttons">
		<button type="submit" class="button button-primary">Add Filter</button>
		or
		<a href="filters">Cancel</a>
	</div>
</form>
