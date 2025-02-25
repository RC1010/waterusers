<?php
require_once('../database/database.php');  // Correct the path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];

    // Connect to the database
    $database = new Database();
    $conn = $database->dbConnection();

    // Delete the user from the database
    $delete_stmt = $conn->prepare("DELETE FROM user WHERE id = :user_id");
    $delete_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($delete_stmt->execute()) {
        // Return a success JSON response
        echo json_encode(['status' => 'success', 'message' => 'User deleted successfully']);
    } else {
        // Return an error JSON response if the deletion fails
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete user.']);
    }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    }
?>
