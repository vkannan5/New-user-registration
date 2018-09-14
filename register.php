<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Registration system PHP and MySQL</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <div class="header">
  	<h2>Register</h2>
  </div>
	
  <form method="post" action="register.php">
  	<?php include('errors.php'); ?>
  	<div class="input-group">
  	  <label>Username</label>
  	  <input type="text" name="username" value="<?php echo $username; ?>" required>
  	</div>

    <div class="input-group">
      <label>First Name</label>
      <input type="text" name="firstname" required>
    </div>

    <div class="input-group">
      <label>Second Name</label>
      <input type="text" name="secondname" required>
    </div>

    <div class="input-group">
      <label>Phone Number</label>
      <input type="text" name="telephone" required>
    </div>

    <div class="input-group">
      <label>status</label>
      <input type="radio" name="status" value="Student" required> Student <br>
      <input type="radio" name="status" value="Teacher" required> Teacher <br>
    </div>

  	<div class="input-group">
  	  <label>Email</label>
  	  <input type="email" name="email" value="<?php echo $email; ?>" required>
  	</div>
  	<div class="input-group">
  	  <label>Password</label>
  	  <input type="password" name="password_1" required>
  	</div>
  	<div class="input-group">
  	  <label>Confirm password</label>
  	  <input type="password" name="password_2" required>
  	</div>
  	<div class="input-group">
  	  <button type="submit" class="btn" name="reg_user">Register</button>
  	</div>
  	<p>
  		Already a member? <a href="login.php">Sign in</a>
  	</p>
  </form>
</body>
</html>