<?php

require '../vendor/autoload.php';
use App\Category;

$status = ["message" => "success"];

if(isset($_POST['name']) && !empty($_POST['name'])) {
    $name = $_POST['name'];

    $category = new Category();

    session_start();
    $user_id = $_SESSION['user_id'];

    if($category->store($name, $user_id)) {
        $status["message"] = "Category stored";
    } else {
        $status["message"] = "Failed to store!";
    }

} else {
    $status["message"] = "Name is required!";
}

echo json_encode($status);

?>