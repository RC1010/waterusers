<?php
require_once('../database/database.php');  // Ensure correct path to the database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_POST['customer_id'];

    // Connect to the database
    $database = new Database();
    $conn = $database->dbConnection();

    // Fetch the user data
    $stmt = $conn->prepare("SELECT id, first_name, last_name, address, number FROM customer WHERE id = :customer_id");
    $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
    $stmt->execute();

    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($customer) {
        echo json_encode(['status' => 'success', 'customer' => $customer]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Customer not found.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
