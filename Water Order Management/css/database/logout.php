<?php
session_start();

// Include the correct path to connection.php
require_once './connection.php';  // Adjust path as necessary

// Create a new instance of the Connection class
$conn = new Connection();

if (isset($_SESSION['user'])) {
    // Get the user ID from the session
    $user_id = $_SESSION['user']['id'];

    // Update the user status to 'inactive'
    $pdo = $conn->getConnection(); // Get the PDO connection
    $stmt = $pdo->prepare("UPDATE user SET status = 'inactive' WHERE id = :id");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    // Remove all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the login page
    header('Location: ../index.php');
    exit();
}
?>
