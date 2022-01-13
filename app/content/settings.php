<?php
	require_once 'lib/settings.php';

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		saveSettings();
		header('Location: settings');
	}

	$settings = getSettings();
?>
<section class="page-header"><h1>Settings</h1></section>

<form method="POST">
	<label for="miniflux_url">Miniflux instance URL</label>
	<input id="miniflux_url" type="text" name="url" placeholder="https://miniflux.example.com" value="<?= $settings['url']; ?>">


	<label for="miniflux_token">Miniflux API token</label>
	<input id="miniflux_token" type="text" name="token" placeholder="123123123" value="<?= $settings['token']; ?>">

	<label for="refresh_freq">Refresh frequency (in minutes)</label>
	<input id="refresh_freq" type="number" name="refresh_freq" value="<?= $settings['refresh_freq']; ?>">

	<div class="buttons">
		<button type="submit" class="button button-primary">Save</button>
	</div>
</form>
