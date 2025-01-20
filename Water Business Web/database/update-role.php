<?php
require_once('../database/database.php');  // Ensure correct path to the database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role_id = $_POST['role_id'];
    $roles = $_POST['role_name'];
    $description = $_POST['role_description'];

    // Validate inputs
    if (empty($roles) || empty($description)) {
        echo json_encode(['status' => 'error', 'message' => 'Role name and description cannot be empty.']);
        exit;
    }

    // Connect to the database
    $database = new Database();
    $conn = $database->dbConnection();

    // Start building the update query
    $update_query = "UPDATE role SET roles = :roles, description = :description, updated_at = NOW() WHERE id = :role_id";

    // Prepare and execute the update query
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bindParam(':roles', $roles, PDO::PARAM_STR);
    $update_stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $update_stmt->bindParam(':role_id', $role_id, PDO::PARAM_INT);

    if ($update_stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Role updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update role.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
