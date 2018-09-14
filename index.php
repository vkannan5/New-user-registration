<?php include('server.php') ?>
<?php 
  $email = "";
  $username="";
  $firstname="";
  $secondname="";
  $telephone="";
  $status="";
  $status_teacher="Teacher";
  $username=$_SESSION['username'];
  $to  = 'dummycertifica@gmail.com';
  $subject = 'New user registration approval';
  
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  $headers .= 'To: Admin <dummycertifica@gmail.com>, Admin <dummycertifica@gmail.com>' . "\r\n";
  $headers .= 'From: Admin <dummycertifica@gmail.com>' . "\r\n";

  $db = mysqli_connect('localhost', 'root', '', 'registration');
  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="header">
	<h2>Settings</h2>
</div>
<div class="content">
  	<!-- notification message -->
  	<?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php 
          	echo $_SESSION['success']; 
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
  	<?php endif ?>

    <!-- logged in user information -->
    <?php  if (isset($_SESSION['username'])) : ?>
      
    	<p align="center">  Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
    	<p align="right"> <a href="index.php?logout='1'" style="color: red;">logout</a> </p>
    <?php endif ?>

    <?php
      $query = "SELECT * FROM users WHERE username='$username'";
      $query2 = "SELECT * FROM teachers WHERE username='$username'";
      $results = mysqli_query($db, $query);
      $results2 = mysqli_query($db, $query2);
      $user = mysqli_fetch_assoc($results);
      $user2 = mysqli_fetch_assoc($results2);
      if($user)
      {
        $email=$user['email'];
        $firstname=$user['firstname'];
        $secondname=$user['secondname'];
        $telephone=$user['telephone'];
        $status=$user['status'];
      }
      elseif($user2)
      {
        $email=$user2['email'];
        $firstname=$user2['firstname'];
        $secondname=$user2['secondname'];
        $telephone=$user2['telephone'];
        $status=$user2['status'];
      }
    ?>

    <form>
    <div class="input-group">
      <label>Username</label>
      <input type="text" name="username" value="<?php echo $_SESSION['username']; ?>" readonly>
    </div>
    <div class="input-group">
      <label>First Name</label>
      <input type="text" name="firstname" value="<?php echo $firstname; ?>" readonly>
    </div>
    <div class="input-group">
      <label>Second Name</label>
      <input type="text" name="secondname" value="<?php echo $secondname; ?>" readonly>
    </div>
    <div class="input-group">
      <label>Phone Number</label>
      <input type="text" name="telephone" value="<?php echo $telephone; ?>" readonly>
    </div>
    <div class="input-group">
      <label>E-Mail</label>
      <input type="email" name="email" value="<?php echo $email; ?>" readonly>
    </div>
    <p>
     <a href="update.php" class="btn">Update Settings</a>
    </p>  
    </form>

</div>
		
</body>
</html>