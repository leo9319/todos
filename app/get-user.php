<?php

require '../vendor/autoload.php';
use App\User;

$user = new User;

session_start();
$user_id = $_SESSION['user_id'];

return User::all($user_id);

?>