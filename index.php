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

?>

<!DOCTYPE html>
<html>
<head>
	<title>Portfolio | Todos</title>
    <?php include_once 'partials/styles.php' ?>
</head>
<body>
	<div id="app">
        <nav-bar></nav-bar>
		<todo-app></todo-app>
	</div>
    <?php include_once 'partials/scripts.php' ?>
	
</body>
</html>
