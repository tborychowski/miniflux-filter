<?php

require_once 'lib/actions.php';
tryToLogin();

?>
<section class="login-form">
	<form method="POST" action="login">
		<label>
			<span>Password</span>
			<input type="password" name="password" autofocus>
		</label>
		<div class="buttons">
			<button type="submit" class="button button-primary">Login</button>
		</div>
	</form>
</section>
