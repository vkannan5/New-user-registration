<?php include('server.php') ?>
<?php 
   
  $email = "";
  $username="";
  $firstname="";
  $secondname="";
  $telephone="";
  $username=$_SESSION['username'];
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
	<h2>Home Page</h2>
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
      
    	<p>Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
    	<p> <a href="index.php?logout='1'" style="color: red;">logout</a> </p>
    <?php endif ?>

    <?php
      $query = "SELECT * FROM users WHERE username='$username'";
      $query2= "SELECT * FROM teachers WHERE username='$username'";
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
      }
      elseif($user2)
      {
        $email=$user2['email'];
        $firstname=$user2['firstname'];
        $secondname=$user2['secondname'];
        $telephone=$user2['telephone'];
      }
    ?>

    <form method="post" action="update.php">
    <?php include('errors.php'); ?>
    Username: <input type="text" name="username" value="<?php echo $_SESSION['username']; ?>" ><br>
    First Name: <input type="text" name="firstname" value="<?php echo $firstname; ?>" > <br>
    Second Name: <input type="text" name="secondname" value="<?php echo $secondname; ?>" > <br>
    Phone Number: <input type="text" name="telephone" value="<?php echo $telephone; ?>" > <br>
    Email: <input type="email" name="email" value="<?php echo $email; ?>" > <br>
    Password: <input type="password" name="password_1"> <br>
    Confirm Password: <input type="password" name="password_2"> <br>


    <button type="submit" class="btn" name="update_user">Update</button>
      
    </form>

</div>
		
</body>
</html>