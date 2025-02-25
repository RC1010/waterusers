<?php
require_once('../database/database.php');  // Ensure correct path to the database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the posted data
    $customer_id = $_POST['customer_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $number = $_POST['number'];

    // Connect to the database
    $database = new Database();
    $conn = $database->dbConnection();

    // Start building the update query
    $update_query = "UPDATE customer SET first_name = :first_name, last_name = :last_name, address = :address, number = :number, updated_at = NOW()";
    

    $update_query .= " WHERE id = :customer_id";
    
    // Prepare the update query
    try {
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
        $update_stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
        $update_stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $update_stmt->bindParam(':number', $number, PDO::PARAM_INT);


        $update_stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);

        // Execute the query
        if ($update_stmt->execute()) {
            // Return a success JSON response
            echo json_encode(['status' => 'success', 'message' => 'Customer updated successfully']);
        } else {
            // Return an error JSON response if the update fails
            echo json_encode(['status' => 'error', 'message' => 'Failed to update customer.']);
        }
    } catch (PDOException $e) {
        // Catch any errors and return an error message
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
