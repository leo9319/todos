<?php

require '../vendor/autoload.php';
use App\Category;

$status = ["message" => "success"];

if(isset($_POST['id']) && !empty($_POST['id'])) {

    $id = $_POST['id'];

    $category = new Category();

    if($category->singleDelete($id)) {
        $status["message"] = "Category deleted!";
    } else {
        $status["message"] = "Failed to delete!";
    }

} else {
    $status["message"] = "Failed to get parameters!";
}

echo json_encode($status);

?>