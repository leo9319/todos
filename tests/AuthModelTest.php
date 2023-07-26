<?php

use PHPUnit\Framework\TestCase;
use App\AuthModel;

class AuthModelTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        parent::setUp();

        // Connect to your MySQL database
        $host = 'localhost'; // Replace with your MySQL host
        $db_name = 'todos'; // Replace with your testing database name
        $username = 'root'; // Replace with your MySQL username
        $password = 'password'; // Replace with your MySQL password

        // Create a PDO connection to the MySQL database
        $this->pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Ensure the users table is empty before running each test
        $this->pdo->exec("DELETE FROM users");
    }

    public function testLoginSuccess()
    {
        $authModel = new AuthModel();

        // Add a test user with valid credentials to the users table
        $hashedPassword = password_hash('testpassword', PASSWORD_DEFAULT);
        $this->pdo->exec("
            INSERT INTO users (username, password, email, is_premium)
            VALUES ('testuser', '{$hashedPassword}', 'testuser@example.com', 1)
        ");

        // Perform login with valid credentials
        $authModel->login('testuser', 'testpassword');

        // Assertions to check if the session variables are set correctly
        $this->assertTrue($_SESSION["loggedin"]);
        $this->assertEquals('testuser', $_SESSION["username"]);
    }

    public function testLoginFailure()
    {
        $authModel = new AuthModel();

        // Add a test user with valid credentials to the users table
        $hashedPassword = password_hash('testpassword', PASSWORD_DEFAULT);
        $this->pdo->exec("
            INSERT INTO users (username, password, email, is_premium)
            VALUES ('testuser', '{$hashedPassword}', 'testuser@example.com', 1)
        ");

        // Perform login with invalid credentials
        $authModel->login('testuser', 'wrongpassword');

        // Assertions to check if the proper session variable is set after failed login
        $this->assertFalse($_SESSION["loggedin"]);
        $this->assertArrayHasKey('login_error', $_SESSION);
    }

    public function testRegisterSuccess()
    {
        $authModel = new AuthModel();

        // Perform registration with valid data
        $authModel->register('newuser', 'newuser@example.com', 'NewPass123!', 'NewPass123!');

        // Assertions to check if the user is inserted into the database and redirected to the correct page
        $stmt = $this->pdo->query("SELECT * FROM users WHERE username = 'newuser'");
        $this->assertEquals(1, $stmt->rowCount());

        // test the session variables here
         $this->assertTrue($_SESSION["loggedin"]);
         $this->assertEquals('newuser', $_SESSION["username"]);
    }

    /**
     * @covers \App\AuthModel::register
     */
    public function testRegisterFailure()
    {
        $authModel = new AuthModel();

        // Perform registration with invalid data
        $authModel->register('existinguser', 'existinguser@example.com', 'InvalidPass123!', 'InvalidPass321!');

        // Assertions to check if the proper session variable is set after failed registration
        $this->assertArrayHasKey('registration_error', $_SESSION);
    }
}
