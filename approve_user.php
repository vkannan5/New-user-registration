<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Approve Users</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <div class="header">
  	<h2>Approve</h2>
  </div>
	
  <form method="post" action="register.php">
  	<?php include('errors.php'); ?>
  	<div class="input-group">
  	  <label>Username</label>
  	  <input type="text" name="username" required>
  	</div>

    <div class="input-group">
      <label>Email</label>
      <input type="text" name="email" required>
    </div>
    <div class="input-group">
      <button type="submit" class="btn" name="app_user"> Approve </button>
    </div>
    <div class="input-group">
      <button type="submit" class="btn" name="reject_user"> Reject </button>
    </div>
 </form>
</body>
</html>