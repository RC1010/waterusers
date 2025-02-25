<?php
require_once('../database/database.php');  // Correct the path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the role_id from the POST data
    if (isset($_POST['role_id']) && !empty($_POST['role_id'])) {
        $role_id = $_POST['role_id'];

        // Connect to the database
        try {
            $database = new Database();
            $conn = $database->dbConnection();

            // Delete the role from the database
            $delete_stmt = $conn->prepare("DELETE FROM role WHERE id = :role_id");
            $delete_stmt->bindParam(':role_id', $role_id, PDO::PARAM_INT);

            if ($delete_stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Role deleted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete role.']);
            }

        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid or missing role_id.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
