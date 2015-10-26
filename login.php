<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <title>W^2 Login Page</title>
    <link href="./bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
include("authenticate.php");

// check to see if user is logging out
if(isset($_GET['out'])) {
	// destroy session
	session_unset();
	$_SESSION = array();
	unset($_SESSION['user'],$_SESSION['access']);
	session_destroy();
}

// check to see if login form has been submitted
if(isset($_POST['userLogin'])){
	// run information through authenticator
	if(authenticate($_POST['userLogin'],$_POST['userPassword']))
	{
		// authentication passed
		//header("Location: index.php");
		//die();
	} else {
		// authentication failed
		$error = 1;
	}
}

// output error to user
if (isset($error)) echo "Login failed: Incorrect user name, password, or rights<br /-->";

// output logout success
//$_SESSION['access'] = 'admin';

if (isset($_SESSION['user'])) { 
	header("Location: index.php");
        die();

	echo '<div class="container">';
	echo '<p class="bg-success">Welcome '.$_SESSION['user'].' <a href="./login.php?out=1">logout</a></p>';
	echo "</div> <!-- /container -->";
} else {
	echo '<div class="container">';
	if (isset($_GET['out'])) echo '<p class="bg-success">Logout successful</p>';
	echo '<form class="form-signin" action="login.php" method="post">';
	echo '<h1 class="form-signin-heading text-center">W<sup>2</sup></h1>';
	echo '<label for="userLogin" class="sr-only">Username</label>';
        echo 'User: <input type="text" id="userLogin" name="userLogin" class="form-control" placeholder="Username" required autofocus/>';
	echo '<label for="userPassword" class="sr-only">Password</label>';
        echo 'Password: <input type="password" id="userPasword" name="userPassword" class="form-control" placeholder="Password" required/>';
        echo '<div class="checkbox">';
        echo '<label>';
        echo '<input type="checkbox" value="remember-me"> Remember me';
        echo '</label>';
        echo '</div>';
	echo '<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>';
	echo "</form>";
	if (isset($error)) {
		echo '<p class="bg-warning">Login failed: Incorrect Username or Password</p>';
	}
	echo "</div> <!-- /container -->";
}
?>
</body>
