<?php 
session_start();

    $db = mysqli_connect('localhost', 'root', '', 'registration');
    $verification="verified";
    if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
    // Verify data
    $email = mysqli_real_escape_string($db, $_GET['email']); // Set email variable
    $hash = mysqli_real_escape_string($db, $_GET['hash']); // Set hash variable
    }
    $user_check_query = "SELECT * FROM users WHERE hash='$hash' OR email='$email' LIMIT 1";
    $user_check_query2 = "SELECT * FROM teachers WHERE hash='$hash' OR email='$email' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $result2 = mysqli_query($db, $user_check_query2);
    $user = mysqli_fetch_assoc($result);
    $user2 = mysqli_fetch_assoc($result2);
    if($user)
    {
      $query = "UPDATE users SET verify='$verification' WHERE email='$email' AND hash='$hash'";
      mysqli_query($db, $query);
    }

    elseif($user2)
    {
      $query = "UPDATE teachers SET verify='$verification' WHERE email='$email' AND hash='$hash'";
      mysqli_query($db, $query);
    }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Verify Account</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <div class="header">
    <h2>Verify</h2>
  </div>
  
  <form method="post" action="register.php">

<p align="center"> 

  <input type="text" name="email" value="<?php echo $email; ?>">
  <br>

  Thank you for verifying your account. You can now access your account by logging in
  using your email and password combination. </p>
    
 </form>
</body>
</html>