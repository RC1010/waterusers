<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once './database/database.php'; // Include Database class

// Initializing Database Connection
$database = new Database();
$conn = $database->dbConnection(); // Get the PDO connection object

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if customer_id is provided
if (isset($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];

    // Check if customer_id is a valid number
    if (!is_numeric($customer_id)) {
        echo json_encode(['error' => 'Invalid customer ID']);
        exit();
    }

    $query = "SELECT first_name, last_name, number FROM customer WHERE id = :customer_id";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':customer_id', $customer_id, PDO::PARAM_INT);

    try {
        $stmt->execute();
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($customer) {
            echo json_encode($customer);
        } else {
            echo json_encode(['error' => 'Customer not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No customer ID provided']);
}
?>
