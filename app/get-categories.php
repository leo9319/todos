<?php

require '../vendor/autoload.php';
use App\Category;

$category = new Category;

session_start();
$user_id = $_SESSION['user_id'];

return Category::all($user_id);

?>