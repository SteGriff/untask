<!DOCTYPE HTML>
<html>
<head>
<title>Login</title>
<?php require "facets/head.php"; ?>

<script type="text/javascript" src="/views/js/jquery.js"></script>
</head>
<body>
<?php global $PATH; require "facets/logo.php"; ?>
	
	<div id="wrap">
		
		<h1>Hey you! Untask is brand new.</h1>
		
		<p><strong>Do not use a password which you use elsewhere.</strong> While your password is encrypted on the server (so I can't see it),
		an attacker may be able to steal it and decrypt it eventually. Your password is stored in plain text on <em>your</em> device.</p>
		<h2>Privacy Policy</h2>
		<p>Your notes are not encrypted. I can see them. I will not share them with anyone,
		unless you've got "Murder Gregg" in there or something. Murder is illegal.</p>
		<form class="big">
		
			<p>
				<label for="Username"><span>Username: </span></label>
				<input type="text" name="Username">
			</p>
			<p>
				<label for="Password"><span>Password: </span></label>
				<input type="password" name="Password">
			</p>
			<p>
				<label for="ConfirmPassword"><span>and again: </span></label>
				<input type="password" name="ConfirmPassword">
			</p>
			<p>
				<label for="Submit"><span></span></label>
				<input type="submit" value="Sign up" name="Submit" id="submitButton">
			</p>
			
		</form>
		
	</div>
	
<script type="text/javascript">
	$(function(){
		$("form").submit(function(e){
			
			creating(true);
			e.preventDefault();
			
			var data = $(this).serializeArray();
			var path = '<?php echo $PATH; ?>';
			var controller = path + '/user';
			
			$.post(
				controller,
				data,
				function(data, ts, xhr){
					if (xhr.status == 201){
						window.location.href = 'registered';
					}
					else{
						failed("Email the admin to say 'the user controller is set up wrong (LU1)'");
					}
					return false;
				}
			)
			.fail(function(data, ts, xhr){
				failed(data.responseText);
				return false;
			});
			
		});
	});
	
	function failed(errorText){
		alert("Failed to register!\n" + errorText);
		creating(false);
	}
	
	function creating(block){
		var $btn = $("input[name=Submit]");
		if (block){
			$btn.val("Thinkin' about it...");
			$btn.attr("disabled", true); 
		}
		else{
			$btn.val("Sign up");
			$btn.removeAttr("disabled", true); 
		}
	}
	
	
	
</script>
</body>
</html>