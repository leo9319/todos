<?php

namespace App;

class Task extends Model {

	private $table = "tasks";

	public static function all($user_id)
	{
		$tasks = array();

		$model = new self();

		$query = "SELECT * FROM $model->table WHERE user_id = '$user_id'";
		if($sql = $model->conn->query($query)) {
			while($row = mysqli_fetch_assoc($sql)) {
				$tasks[] = $row;
			}
		}

		echo json_encode($tasks);
	}

	public function store($name, $user_id, $category_id)
	{
		$query = "INSERT INTO $this->table (name, user_id, category_id) VALUES ('$name', '$user_id', '$category_id')";

		return $sql = $this->conn->query($query);

	}

	public function update($id, $name) 
	{
		$query = "UPDATE $this->table SET name = '$name' WHERE id = '$id'";

		return $sql = $this->conn->query($query);

	}

	public function delete($ids) 
	{
		$ids = implode("','", explode(',', $ids));

		$query = "DELETE FROM $this->table WHERE id IN ('".$ids."')";

		return $sql = $this->conn->query($query);

	}

	public function singleDelete($id) 
	{

		$query = "DELETE FROM $this->table WHERE id = $id";

		return $sql = $this->conn->query($query);

	}

    public function totalTasks($user_id) {
        $model = new self();

        $query = "SELECT COUNT(*) as count FROM {$model->table} WHERE user_id = '$user_id'";
        if ($sql = $model->conn->query($query)) {
            $row = mysqli_fetch_assoc($sql);
            $count = $row['count'];
            return $count;
        }

        return 0; // Return 0 if the query fails or no rows are found
    }
}