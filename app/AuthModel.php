<?php

namespace App;

require_once 'Model.php';

class AuthModel extends Model {

    public function login($username, $password) {
        $conn = $this->getConnection();

        // Check username and password against the database and perform login if valid
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($userId, $hashedPassword);

        if ($stmt->fetch() && password_verify($password, $hashedPassword)) {
            // Password is correct, set a session variable to mark the user as logged in
            session_start();
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $username;
            $_SESSION["user_id"] = $userId;
            header("location: index.php");
            exit;
        } else {
            echo "Invalid username or password";
        }

        $stmt->close();
    }

    public function register($username, $email, $password) {
        $conn = $this->getConnection();
        // Check if the username and email already exist in the database
        // Insert the new user's information into the database if the username and email are unique

        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "Username or email already exists";
        } else {
            // Insert the new user data into the database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashedPassword, $email);
            if ($stmt->execute()) {
                echo "Registration successful! You can now log in.";
            } else {
                echo "Error during registration. Please try again.";
            }
        }

        $stmt->close();
    }

    public function logout() {
        // Destroy the session to log the user out
        session_start();
        $_SESSION = array();
        session_destroy();
        header("Location: login.php");
        exit;
    }
}
