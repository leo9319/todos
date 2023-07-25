<?php

require 'vendor/autoload.php';

use App\AuthModel;

$authModel = new AuthModel();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["login"])) {
        // Handle login form submission
        $submitted_username = $_POST["username"];
        $submitted_password = $_POST["password"];

        // Call the login method from AuthModel
        $authModel->login($submitted_username, $submitted_password);
    } elseif (isset($_POST["register"])) {
        // Handle registration form submission

        $submitted_username = $_POST["username"];
        $submitted_email = $_POST["email"];
        $submitted_password = $_POST["password"];
        $submitted_confirm_password = $_POST["confirm_password"];

        // Call the register method from AuthModel
        $authModel->register($submitted_username, $submitted_email, $submitted_password);
    }
} elseif (isset($_GET["logout"])) {
    // Handle logout request
    $authModel->logout();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login | Register</title>
    <!-- Include necessary CSS and scripts -->
</head>
<body>
<h1>Login</h1>
<form method="post" action="login.php">
    <!-- Login form fields -->
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
</form>

<h1>Register</h1>
<form method="post" action="login.php">
    <!-- Registration form fields -->
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email address" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
    <button type="submit" name="register">Register</button>
</form>

<?php
// Display "Logout" link if the user is logged in
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    echo '<a href="login.php?logout=true">Logout</a>';
}
?>
</body>
</html>
