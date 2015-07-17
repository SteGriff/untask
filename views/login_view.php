<!DOCTYPE HTML>
<html>
<head>
<title>Login</title>
<?php require "facets/head.php"; ?>
</head>
<body>
<?php global $PATH; require "facets/logo.php"; ?>
	
	<div id="wrap">
		
		<h1>
		<?php
		if (isset($this->message)) {
			echo $this->message;
		}
		else{
			echo "Log in";
		}
		?>
		</h1>
		
		<form method="post" action="/login" class="big">
		
			<p><label>
				<span>User: </span><input type="text" name="username">
			</label></p>
			<p><label>
				<span>Pass: </span><input type="password" name="password">
			</label></p>
			<p><label>
				<span></span><input type="submit" value="Login">
			</label></p>
		
		</form>
		
		<p class="center">
			<a href="/register">Register</a>
		</p>
		
	</div>
</body>
</html>