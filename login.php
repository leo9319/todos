<?php
// Replace these values with your actual database credentials
$host = 'localhost';
$dbname = 'todos';
$username = 'root';
$password = 'password';

// Attempt to create a PDO database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Function to hash the password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Function to verify the password
function verifyPassword($password, $hashedPassword) {
    return password_verify($password, $hashedPassword);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["login"])) {
        // Handle login form submission
        $submitted_username = $_POST["username"];
        $submitted_password = $_POST["password"];

        // Retrieve the hashed password from the database based on the submitted username
        $stmt = $pdo->prepare("SELECT password FROM users WHERE username = :username");
        $stmt->bindParam(":username", $submitted_username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // If the user exists, verify the submitted password with the hashed password from the database
            $hashedPassword = $row["password"];
            if (verifyPassword($submitted_password, $hashedPassword)) {
                // Password is correct, set a session variable to mark the user as logged in
                session_start();
                $_SESSION["loggedin"] = true;
                header("location: index.php");
                exit;
            } else {
                echo "Invalid password";
            }
        } else {
            echo "User not found";
        }
    } elseif (isset($_POST["register"])) {
        // Handle registration form submission

        $submitted_username = $_POST["username"];
        $submitted_email = $_POST["email"];
        $submitted_password = $_POST["password"];
        $submitted_confirm_password = $_POST["confirm_password"];

        // Check if the username already exists in the database
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->bindParam(":username", $submitted_username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            echo "Username already exists";
        } elseif ($submitted_password !== $submitted_confirm_password) {
            echo "Passwords do not match";
        } else {
            // Insert the new user data into the database
            $hashedPassword = hashPassword($submitted_password);

            $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
            $stmt->bindParam(":username", $submitted_username);
            $stmt->bindParam(":password", $hashedPassword);
            $stmt->bindParam(":email", $submitted_email);
            $stmt->execute();

            echo "Registration successful! You can now log in.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login | Register</title>
    <!-- Include necessary CSS and scripts -->
</head>
<body>
<h1>Login</h1>
<form method="post" action="login.php">
    <!-- Login form fields -->
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
</form>

<h1>Register</h1>
<form method="post" action="login.php">
    <!-- Registration form fields -->
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email address" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
    <button type="submit" name="register">Register</button>
</form>
</body>
</html>
