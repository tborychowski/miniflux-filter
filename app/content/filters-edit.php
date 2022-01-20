<?php
	require_once 'lib/actions.php';
	require_once 'lib/miniflux.php';
	require_once 'lib/settings.php';
	require_once 'lib/filters.php';
	require_once 'lib/utils.php';

	$filters = getFilters();
	$settings = getSettings();
	if (!isSettingsSet()) return goToSettings();

	$m = new Miniflux($settings);
	$feeds = $m->getFeedTitles();

	$id = $_REQUEST['id'] ?? -1;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$was_changed = updateFilter($filters, $_POST, $feeds, $id);
		if ($was_changed) header('Location: filter-job.php');
		else header('Location: filters');
	}

	$filter = get_item_by_id($filters, $id) ?? [];
	$isFeedSelected = fn($f) => $filter['feed_id'] == $f ? 'selected' : '';
	$isFieldSelected = fn($f) => $filter['field'] == $f ? 'selected' : '';

	require_once 'filters-edit-menu.php';
?>

<form method="POST">
	<label for="feed">Feed</label>
	<select id="feed" name="feed">
		<?php
			foreach ($feeds as $feed) {
				$feed_id = $feed['id'];
				$title = $feed['title'];
				$selected = $isFeedSelected($feed_id);
				echo "<option value=\"$feed_id\" $selected>$title</option>";
			}
			?>
	</select>

	<label for="field">Field</label>
	<select id="field" name="field">
		<option value="title" <?= $isFieldSelected('title') ?>>Title</option>
		<option value="content" <?= $isFieldSelected('content') ?>>Content</option>
	</select>

	<label for="match">Match</label>
	<input id="match" type="text" name="match" value="<?= $filter['match'] ?>" placeholder="e.g. Sponsored">

	<div class="buttons">
		<button type="submit" class="button button-primary">Update Filter</button>
		or
		<a href="filters">Cancel</a>
	</div>
</form>
