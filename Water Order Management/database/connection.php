<?php
require_once('database.php'); // Correct path if both files are in the same folder

class Connection {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->dbConnection(); // Get the PDO connection object
    }

    public function getConnection() {
        return $this->conn; // Provide a method to access the PDO connection
    }

    public function getAllUsers() {
        $query = "SELECT * FROM user";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function loginUser($email, $password) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM user WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return false; // Email not found
            }

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return false; // No user found
            }

            $hashed_password = $user['password']; // Assuming password is stored in the 'password' column

            if (!password_verify($password, $hashed_password)) {
                return false; // Password is incorrect
            }

            return $user;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
?>
