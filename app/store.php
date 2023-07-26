<?php

	require '../vendor/autoload.php';
	use App\Task;

	$status = ["message" => "success"];

	if(isset($_POST['name']) && !empty($_POST['name'])) {
		$name = $_POST['name'];
		$cat_id = $_POST['cat_id'];

		$task = new Task();

        session_start();
        $user_id = $_SESSION['user_id'];
        $is_premium = $_SESSION['is_premium'];

        if(!$is_premium) {
            $total_tasks = $task->totalTasks($user_id);

            if($total_tasks >= 5) {
                $status["message"] = "Limit is crossed! Please purchase the premium";
                http_response_code(400);
                echo json_encode($status);
                exit;
            }
        }


		if($task->store($name, $user_id, $cat_id)) {
			$status["message"] = 'Task created!';
		} else {
			$status["message"] = "Failed to store!";
		}
		
	} else {
		$status["message"] = "Name is required!";
	}

	echo json_encode($status);

?>