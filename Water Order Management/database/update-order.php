<?php
require_once('../database/database.php'); // Ensure correct path to the database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $order_id = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);
    $quantity_ordered = filter_input(INPUT_POST, 'quantity_ordered', FILTER_VALIDATE_INT);
    $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);

    if ($order_id && $quantity_ordered !== false && $amount !== false) {
        // Connect to the database
        $database = new Database();
        $conn = $database->dbConnection();

        try {
            // Update the quantity_ordered field
            $update_query = "UPDATE customer_orders 
                             SET quantity_ordered = :quantity_ordered, 
                             amount = :amount,
                             updated_at = NOW() WHERE id = :order_id";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bindParam(':quantity_ordered', $quantity_ordered, PDO::PARAM_INT);
            $update_stmt->bindParam(':amount', $amount, PDO::PARAM_STR); // Use PARAM_STR for decimals
            $update_stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);

            if ($update_stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Quantity updated successfully']);
            } else {
                error_log("Failed to update order ID: $order_id");
                echo json_encode(['status' => 'error', 'message' => 'Failed to update quantity.']);
            }
            
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input data.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
