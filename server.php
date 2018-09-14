<?php
session_start();

// initializing variables
$username = "";
$email    = "";
$status = "";
$telephone="";
$firstname="";
$secondname="";
$verify="";
$to_user="";
$hash="";
$subject="Verify Account";
$verification="verified";
$not_verification="not_verified";
$status_student="Student";
$status_teacher="Teacher";
$status_admin="admin";
$to  = 'dummycertifica@gmail.com';
$subject = 'New user registration approval';
  
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//$headers .= 'To: Admin <dummycertifica@gmail.com>, Admin <dummycertifica@gmail.com>' . "\r\n";
//$headers .= 'From: Admin <dummycertifica@gmail.com>' . "\r\n";

$errors = array(); 
$prints = array();

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'registration');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
  $firstname = mysqli_real_escape_string($db, $_POST['firstname']);
  $secondname = mysqli_real_escape_string($db, $_POST['secondname']);
  $status = mysqli_real_escape_string($db, $_POST['status']);
  $telephone = mysqli_real_escape_string($db, $_POST['telephone']);
  
  // validate input data to ensure form is filled correctly
  // Add errors to array to keep a count of the errors
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if (empty($firstname)) { array_push($errors, "First Name is required"); }
  if (empty($secondname)) { array_push($errors, "Second Name is required"); }
  if (empty($telephone)) { array_push($errors, "Phone Number is required"); }
  if (empty($status)) { array_push($errors, "Status is required"); }

  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  // check if the same username or email exists
  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
  $user_check_query2 = "SELECT * FROM teachers WHERE username='$username' OR email='$email' LIMIT 1";
  
  $result = mysqli_query($db, $user_check_query);
  $result2 = mysqli_query($db, $user_check_query2);

  $user = mysqli_fetch_assoc($result);
  $user2 = mysqli_fetch_assoc($result2);
  $hash = md5( rand(0,1000) );
  
  if ($user){ // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }    
    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }    
  }

  if($user2)
  {
    if ($user2['username'] === $username) {
      array_push($errors, "Username already exists");
    }
    if ($user2['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }

  // If there are no errors, register the user into the database
  if (count($errors) == 0) {
    
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO users (username, email, password, status, firstname, secondname, telephone, verify, hash) 
  			  VALUES('$username', '$email', '$password', '$status', '$firstname', '$secondname', '$telephone', '$not_verification','$hash')";
  	mysqli_query($db, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "Your Profile";
  	header('location: login.php');
    $to_user=$email;

    $message='
                    <a href="http://localhost/users/verify.php?email='.$email.'&hash='.$hash.'"> Click Here to verify your account. </a>'
                    ;
      if(mail($to_user,$subject,$message, $headers)){
            echo "A mail has been sent to verify your account. Click on the link in the mail to verify your account.";
          }
      else{
            echo "Logging in as a student";
          }    

    if($status === $status_teacher)
    {
      $message="Username: <u>$username</u>. <br>
                    E-Mail: <u>$email</u>. <br>
                    <a href='http://localhost/users/login_approve.php'> Click Here to Approve the User </a>"
                    ;
      if(mail($to,$subject,$message, $headers)){
            echo "You are presently logged in as a student. Please wait for Admin approval to be granted a Teacher's account";
          }
      else{
            echo "Logging in as a student";
          }        
    }
  }

}

if (isset($_POST['update_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
  $firstname = mysqli_real_escape_string($db, $_POST['firstname']);
  $secondname = mysqli_real_escape_string($db, $_POST['secondname']);
  $status = mysqli_real_escape_string($db, $_POST['status']);
  $telephone = mysqli_real_escape_string($db, $_POST['telephone']);


  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if (empty($firstname)) { array_push($errors, "First Name is required"); }
  if (empty($secondname)) { array_push($errors, "Second Name is required"); }
  if (empty($telephone)) { array_push($errors, "Phone Number is required"); }
  
  if ($password_1 != $password_2) {
  array_push($errors, "The two passwords do not match");
  }

  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
  $user_check_query2 = "SELECT * FROM teachers WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $result2 = mysqli_query($db, $user_check_query2);
  $user = mysqli_fetch_assoc($result);
  $user2 = mysqli_fetch_assoc($result2);
  
  if (count($errors) == 0) {
    $password = md5($password_1);
    if($user)
    {
      $query = "UPDATE users SET email='$email', telephone='$telephone', firstname='$firstname', secondname='$secondname', password='$password'  WHERE username='$username'";
      mysqli_query($db, $query);
      $_SESSION['username'] = $username;
      $_SESSION['success'] = "You are now logged in";
      header('location: index.php');
    }
    elseif ($user2) 
    {
      $query2 = "UPDATE teachers SET email='$email', telephone='$telephone', firstname='$firstname', secondname='$secondname', password='$password'  WHERE username='$username'";
      mysqli_query($db, $query2);
      $_SESSION['username'] = $username;
      $_SESSION['success'] = "You are now logged in";
      header('location: index.php');
    }
  }
}


if (isset($_POST['login_app_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
    array_push($errors, "Username is required");
  }
  if (empty($password)) {
    array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
    $password = md5($password);
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $results = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($results);
    if($user)
    {
      if (mysqli_num_rows($results) == 1) 
          {
            $_SESSION['username'] = $username;
            //$_SESSION['success'] = "You are now logged in as a student";
            header('location: approve_user.php');
          }else 
          {
            array_push($errors, "Wrong username/password combination");
          }
    }
  }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
    array_push($errors, "Username is required");
  }
  if (empty($password)) {
    array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
    $password = md5($password);
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $query2 = "SELECT * FROM teachers WHERE username='$username' AND password='$password'";
    $results = mysqli_query($db, $query);
    $results2 = mysqli_query($db, $query2);
    $user = mysqli_fetch_assoc($results);
    $user2 = mysqli_fetch_assoc($results2);
    if($user)
    {
      if($user['verify']===$verification)
      { 

              if($user['status']===$status_student)
              {
                  if (mysqli_num_rows($results) == 1) 
                  {
                    $_SESSION['username'] = $username;
                    $_SESSION['success'] = "You are now logged in as a student";
                    header('location: index.php');
                  }else 
                  {
                    array_push($errors, "Wrong username/password combination");
                  }
              }
                      
              if($user['status']===$status_admin)
              {
                array_push($prints, "You will be logging in as a administrator");
                if (mysqli_num_rows($results) == 1) 
                {
                  $_SESSION['username'] = $username;
                  $_SESSION['success'] = "You are now logged in as an admin";
                  header('location: index.php');
                }else 
                {
                  array_push($errors, "Wrong username/password combination");
                }    

              }
              
              if($user['status']===$status_teacher)
              {
                array_push($prints, "You will be logging in as a Student until approved by the Admin");
                if (mysqli_num_rows($results) == 1) 
                {
                  $_SESSION['username'] = $username;
                  $_SESSION['success'] = "You are presently logged in as an Student. Please wait for admin approval.";
                  header('location: index.php');
                }else {
                  array_push($errors, "Wrong username/password combination");
                }    
              } 
        }  

        else
        {
           array_push($errors, "Your account hasnt been verified. Please verify before logging in.");
        }
    }
    elseif($user2)
      {
              
          if($user2['verify']==$verification)
          {
              array_push($prints, "You will be logging in as a Teacher");
                if (mysqli_num_rows($results2) == 1) 
                {
                  $_SESSION['username'] = $username;
                  $_SESSION['success'] = "You are now logged in as a teacher.";
                  header('location: index.php');
                }else 
                {
                  array_push($errors, "Wrong username/password combination");
                }
            }
            else
            {
              array_push($errors, "Your account hasnt been verified. Please verify before logging in.");
            }  
      }
    else 
    {
            array_push($errors, "Wrong username/password combination");
    }   
  }
}

if (isset($_POST['app_user'])) 
{

  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) 
  { // if user exists
    $status=$user['status'];
    $password=$user['password'];
    $email=$user['email'];
    $telephone=$user['telephone'];
    $firstname=$user['firstname'];
    $secondname=$user['secondname'];
    $verify=$user['verify'];
    $hash=$user['hash'];
    //Add users into teachers database
    $query = "INSERT INTO teachers (username, email, password, status, firstname, secondname, telephone, verify, hash) 
          VALUES('$username', '$email', '$password', '$status', '$firstname', '$secondname', '$telephone','$verify', '$hash')";
    mysqli_query($db, $query);
    //Remove user from Students database
    $query2 = "DELETE FROM users WHERE username = '$username'";
    mysqli_query($db, $query2);

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $subject='Account Approved';
    $message="
                    Your account has been approved. You can now login as a teacher. "
                    ;
    $to_user=$email;
    if(mail($to_user,$subject,$message, $headers)){
            echo "A mail has been sent to the user.";
          }
      else{
            echo "Logging in as a student";
          }
    header('location: user_approved.php');
  }
  
}

if (isset($_POST['reject_user'])) 
{

  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  if($user)
  {
    $email=$user['email'];
  }
  $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $subject='Account Approved';
    $message="
                    Your access to teacher account has been Rejected. You can still login as a student. "
                    ;
    $to_user=$email;
    if(mail($to_user,$subject,$message, $headers)){
            echo "A mail has been sent to the user.";
          }
      else{
            echo "Something went wrong";
          }
    //header('location: user_approved.php');
}

if (isset($_POST['verify_acc'])) {
  
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $user_check_query = "SELECT * FROM users WHERE username='$username' LIMIT 1";
  $user_check_query2 = "SELECT * FROM teachers WHERE username='$username' LIMIT 1";
  
  $result = mysqli_query($db, $user_check_query);
  $result2 = mysqli_query($db, $user_check_query2);

  $user = mysqli_fetch_assoc($result);
  $user2 = mysqli_fetch_assoc($result2);

  if($user)
  {
    $query = "UPDATE users SET verify='$verification' WHERE username='$username'";
    mysqli_query($db, $query);
  }
  elseif ($user2) {

    $query = "UPDATE teachers SET verify='$verification' WHERE username='$username'";
    mysqli_query($db, $query);
    }  

  }
?>