<?php
require_once('../database/database.php');  // Ensure correct path to the database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $role_id = $_POST['role_id'];
    $password = isset($_POST['password']) ? $_POST['password'] : null; // Check if password is provided

    // Connect to the database
    $database = new Database();
    $conn = $database->dbConnection();

    // Start building the update query
    $update_query = "UPDATE user SET first_name = :first_name, last_name = :last_name, email = :email, role_id = :role_id, updated_at = NOW()";
    
    // Only update password if provided
    if ($password) {
        $update_query .= ", password = :password";
    }
    
    $update_query .= " WHERE id = :user_id";
    
    // Prepare and execute the update query
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
    $update_stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
    $update_stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $update_stmt->bindParam(':role_id', $role_id, PDO::PARAM_INT);

    if ($password) {
        // If password is provided, hash it
        $update_stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
    }

    $update_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($update_stmt->execute()) {
        // Return a success JSON response instead of redirect
        echo json_encode(['status' => 'success', 'message' => 'User updated successfully']);
    } else {
        // Return an error JSON response if the update fails
        echo json_encode(['status' => 'error', 'message' => 'Failed to update user.']);
    }
    } else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    }
?>

