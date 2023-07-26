<?php

require 'vendor/autoload.php';
use App\AuthModel;
$authModel = new AuthModel();

session_start();

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Redirect to the login page if the user is not logged in
    header("location: login.php");
    exit;
}

if (isset($_GET["logout"])) {
    // Handle logout request
    $authModel->logout();
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Todos | Categories</title>
    <?php include_once 'partials/styles.php' ?>
</head>
<body>

<div id="app">
    <nav-bar></nav-bar>
    <categories></categories>
</div>

<?php include_once 'partials/scripts.php' ?>

</body>
</html>
