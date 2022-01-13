<?php
	require_once 'lib/actions.php';
	require_once 'lib/settings.php';
	require_once 'lib/filters.php';
	require_once 'lib/utils.php';

	$filters = getFilters();
	$settings = getSettings();
	if (!isSettingsSet()) return goToSettings();

	require_once 'filters-menu.php';


	if (empty($filters)) {
		echo '<p class="alert">There are no filters.</p>';
	}
	else {
		echo '<div class="items">';
			foreach ($filters as $idx => $filter) {
				$id = $filter['id'];
				$feed_id = $filter['feed_id'];
				$title = $filter['feed_title'];
				$field = $filter['field'];
				$match = $filter['match'] ?? '';
				$count = $filter['filtered_count'];
				$created = $filter['created'];
				$created_ago = time_ago($filter['created']);
				$updated = $filter['updated'];
				$updated_ago = time_ago($filter['updated']);
			?>

			<article role="article" class="item ">
				<div class="item-header" dir="auto">
					<span class="item-title">
						<img src="feed-icon.php?id=<?= $feed_id ?>" loading="lazy" alt="<?= $title ?>" width="16" height="16">
						<a href="filters-edit?id=<?= $id ?>"><?= $title ?></a>
					</span>
				</div>
				<div class="item-meta">
					<ul class="item-meta-info">
						<li><time datetime="<?= $created ?>" title="<?= $created ?>">Created <?= $created_ago ?></time></li>
						<li><time datetime="<?= $updated ?>" title="<?= $updated ?>">Updated <?= $updated_ago ?></time></li>
						<li>Filtered articles: <?= $count ?></li>
					</ul>

					<p class="item-meta-description"><?= ucfirst($field) . ': <em>' . $match . '</em>' ?></p>

					<ul class="item-meta-icons">
						<li>
							<a href="filters-edit?id=<?= $id ?>">
								<svg class="icon" aria-hidden="true"><use xlink:href="assets/sprite.svg#icon-edit"></use></svg>
								<span class="icon-label">Edit</span>
							</a>
						</li>
						<li>
							<a href="#"
								data-confirm="true"
								data-label-question="Are you sure?"
								data-label-yes="yes"
								data-label-no="no"
								data-label-loading="In progress..."
								data-url="filters-remove?id=<?= $id ?>">

								<svg class="icon" aria-hidden="true"><use xlink:href="assets/sprite.svg#icon-delete"></use></svg>
								<span class="icon-label">Remove</span>
							</a>
						</li>
					</ul>
				</div>
			</article>
			<?php
			}
		echo '</div>';
	}
