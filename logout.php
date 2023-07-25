<?php
// Start the session to access session variables
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect the user back to the login page or any other page as needed
header("Location: login.php");
exit;
?>