<?php
require_once('../database/database.php');
$database = new Database();
$conn = $database->dbConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];

    // Fetch the order data by ID
    $sql = "SELECT * FROM customer_orders WHERE id = :order_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($order) {
            echo json_encode(['status' => 'success', 'order' => $order]);
        } else {
            // Log an error if the order is not found
            error_log("Order not found for ID: $order_id");
            echo json_encode(['status' => 'failure', 'message' => 'Order not found']);
        }
    } else {
        // Log an error if the query execution fails
        error_log("Failed to execute query for ID: $order_id");
        echo json_encode(['status' => 'failure', 'message' => 'Query execution failed']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
