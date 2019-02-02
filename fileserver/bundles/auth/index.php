<?php require('../../lib/Util.php'); ?>
<!DOCTYPE html>
<html>
	<head>
	<?php HTMLApp::putHeaders('Login'); ?>
	<script type="text/javascript" src="js/auth.js"></script>
	<script type="text/javascript">
	function login()
	{
		var auth = new Auth();
		auth.login(document.getElementById('usr').value,document.getElementById('pwd').value)
	}
	</script>
	<body>
		<div class="jumbotron col-sm-6 mx-auto">
			<h1>Login</h1>
			<div class="form">
				<div class="form-group">
					<label for="usr">Usuario:</label>
					<input class="form-control" id="usr" />
				</div>
				<div class="form-group">
					<label for="pwd">Password:</label>
					<input class="form-control" type="password" id="pwd" />
				</div>
				<div class="form-group">
					<button class="btn btn-primary" onclick="login()" >Login</button>
					<input type="reset" class="btn" />
				</div>
			</div>
        </div>
	</body>
</html>