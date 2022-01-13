<?php
require_once 'lib/actions.php';
require_once 'lib/utils.php';
require_once 'lib/logger.php';

function navItemClassActive ($url) {
	if (empty(getPath()) && $url == 'filters') echo 'active';
	else echo getPath() == $url ? 'active' : '';
}

$logger = new Logger();

if (getPath() != 'login') {
?>

	<header class="header">
		<nav>
			<div class="logo">
				<a href="./">Miniflux<span>Filter</span></a>
			</div>
			<ul>
				<li class="<?= navItemClassActive('filters') ?>"><a href="filters">Filters</a></li>
				<li class="<?= navItemClassActive('settings') ?>"><a href="settings">Settings</a></li>
				<?php
					if (ADMIN_PASSWORD) echo '<li><a href="logout">Logout</a></li>';
				?>
			</ul>


			<div class="search">
				Cron job last run <?= $logger->last_log_time() ?><br>
			</div>
		</nav>

	</header>

<?php } ?>
