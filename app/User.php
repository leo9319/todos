<?php

namespace App;

class User extends Model
{
    private $table = "users";

    public static function all($user_id)
    {
        $user = array();

        $model = new self();

        $query = "SELECT * FROM $model->table WHERE id = '$user_id'";
        if ($sql = $model->conn->query($query)) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $user[] = $row;
            }
        }

        echo json_encode($user);
    }

    public function set_premium($user_id)
    {
        $query = "UPDATE $this->table SET is_premium = 1 WHERE id = '$user_id'";
        return $sql = $this->conn->query($query);

    }
}