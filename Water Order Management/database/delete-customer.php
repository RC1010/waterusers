<?php
require_once('../database/database.php');  // Correct the path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user_id from the POST data
    if (isset($_POST['customer_id']) && !empty($_POST['customer_id'])) {
        $customer_id = $_POST['customer_id'];

        // Connect to the database
        try {
            $database = new Database();
            $conn = $database->dbConnection();

            // Delete the user from the database
            $delete_stmt = $conn->prepare("DELETE FROM customer WHERE id = :customer_id");
            $delete_stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);

            // Execute the query and check for success
            if ($delete_stmt->execute()) {
                // Return a success JSON response
                echo json_encode(['status' => 'success', 'message' => 'Customer deleted successfully']);
            } else {
                // Return an error JSON response if the deletion fails
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete customer.']);
            }
        } catch (PDOException $e) {
            // Catch any database connection or query errors
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        // If user_id is not provided or invalid
        echo json_encode(['status' => 'error', 'message' => 'Invalid or missing customer_id.']);
    }
} else {
    // If the request method is not POST
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
