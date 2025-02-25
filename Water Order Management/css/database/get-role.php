<?php
require_once('../database/database.php');
$database = new Database();
$conn = $database->dbConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role_id = $_POST['role_id'];

    // Fetch the role data by ID
    $sql = "SELECT * FROM role WHERE id = :role_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':role_id', $role_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $role = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the role data
        if ($role) {
            echo json_encode(['status' => 'success', 'role' => $role]); // Return the correct variable
        } else {
            // Log an error if the role is not found
            error_log("Role not found for ID: $role_id");
            echo json_encode(['status' => 'failure', 'message' => 'Role not found']);
        }
    } else {
        // Log an error if the query execution fails
        error_log("Failed to execute query for ID: $role_id");
        echo json_encode(['status' => 'failure', 'message' => 'Query execution failed']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
