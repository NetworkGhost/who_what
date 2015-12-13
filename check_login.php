<?php
//Start session if not already started
session_start();
//Check to see if user is logged in
//If not, redirect to login page
if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        die();
}
?>

