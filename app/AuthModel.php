<?php

namespace App;

require_once 'Model.php';

class AuthModel extends Model {

    /**
     * @param $username
     * @param $password
     * @return void
     */
    public function login($username, $password) {
        $conn = $this->getConnection();

        // Check username and password against the database and perform login if valid
        $stmt = $conn->prepare("SELECT id, password, is_premium FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($userId, $hashedPassword, $isPremium);

        if ($stmt->fetch() && password_verify($password, $hashedPassword)) {
            // Password is correct, set a session variable to mark the user as logged in
            session_start();
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $username;
            $_SESSION["user_id"] = $userId;
            $_SESSION["is_premium"] = $isPremium;
            header("location: index.php");
            exit;
        } else {
            $_SESSION["login_error"] = "Invalid username or password";
        }

        $stmt->close();
    }

    /**
     * @param $username
     * @param $email
     * @param $password
     * @param $confirm_password
     * @return void
     */
    public function register($username, $email, $password, $confirm_password) {
        if ($password !== $confirm_password) {
            $_SESSION["registration_error"] = "Password and Confirm Password do not match.";
            return;
        }

        // Check if the password meets the criteria
        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
            $_SESSION["registration_error"] = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character.";
            return;
        }

        $conn = $this->getConnection();
        // Check if the username and email already exist in the database
        // Insert the new user's information into the database if the username and email are unique

        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION["registration_error"] = "Username or email already exists";
        } else {
            // Insert the new user data into the database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashedPassword, $email);
            if ($stmt->execute()) {
                header("Location: index.php");
            } else {
                $_SESSION["registration_error"] = "Invalid username or password";
            }
        }

        $stmt->close();
    }

    /**
     * @return void
     */
    public function logout() {
        // Destroy the session to log the user out
        session_start();
        $_SESSION = array();
        session_destroy();
        header("Location: login.php");
        exit;
    }
}
