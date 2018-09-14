<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<form method="post">
	<p align="center"> Please enter the email id asscoiated with your account. A temporary password will be emailed to your email id. </p>
	<div class="input-group">
	      <label>Email ID</label>
	      <input type="text" name="email" required>
	</div>
	<div class="input-group">
  	  <button type="submit" class="btn" name="forgotpassword">Get Password</button>
  	</div>
</form>
</body>
</html>

<?php
$db = mysqli_connect('localhost', 'root', '', 'registration');
if (isset($_POST['forgotpassword'])) {
	$email=mysqli_real_escape_string($db, $_POST['email']);
	$password="";
	$user_check_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
  	$user_check_query2 = "SELECT * FROM teachers WHERE email='$email' LIMIT 1";
  	$result = mysqli_query($db, $user_check_query);
  	$result2 = mysqli_query($db, $user_check_query2);
  	$user = mysqli_fetch_assoc($result);
  	$user2 = mysqli_fetch_assoc($result2);
  	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$subject='temporary password';

 	if($user)
  	{
  		$temp_hash=rand(1000,5000);
  		$password = md5($temp_hash);
  		$user_check_query = "UPDATE users SET password='$password' WHERE email='$email'";
  		mysqli_query($db, $user_check_query);
  		$message="
                    Your new password is Password: <u>$temp_hash</u>. "
                    ;
        $to_user=$email;
        if(mail($to_user,$subject,$message, $headers)){
            echo "A mail has been sent to verify your account. Click on the link in the mail to verify your account.";
          }
      else{
            echo "Logging in as a student";
          } 

  	}
  	if($user2)
  	{
  		$password = md5(rand(1000,5000));
  		$user_check_query = "UPDATE teachers SET password='$password' WHERE email='$email'";
  		mysqli_query($db, $user_check_query);
  	}
}

?>