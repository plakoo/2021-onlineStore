

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/LogStyle.css">
  <title>Login</title>
</head>
<body>
	<form action = "loginAction.php" method = "post">
		<div class="login-wrapper">
		  <div class="header">Login</div>
		  <div class="form-wrapper">
			<input type="text" name="username" placeholder="用户名" class="input-item">
			<input type="password" name="password" placeholder="密码" class="input-item">
			<div>
				<button class = "btn">Login</button>
			</div>
		  </div>
		  <div class="msg">
			没有账号? <a href="signup.php">sign up</a>
		  </div>
		</div>
	</form>
</body>
</html>