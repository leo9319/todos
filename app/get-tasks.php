<?php

require '../vendor/autoload.php';
use App\Task;

$task = new Task;

session_start();
$user_id = $_SESSION['user_id'];

return Task::all($user_id);

?>