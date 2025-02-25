<?php
// Start the Session
session_start();

// Include necessary files
include('table_columns.php'); // Ensure this file contains the correct table column mappings
require_once '../database/Database.php'; // Adjust the path as necessary

// Initialize Database connection
$database = new Database();
$conn = $database->dbConnection(); // Get the PDO connection object

// Capture the table name and column mappings from the session
$table_name = $_SESSION['table'] ?? null; // Ensure 'table' is set in session
if (!$table_name || !isset($table_columns_mapping[$table_name])) {
    die(json_encode(['success' => false, 'message' => 'Invalid table name or table columns mapping not found.']));
}

$columns = $table_columns_mapping[$table_name];

// Ensure user session is valid
$user = $_SESSION['user'] ?? null;
if (!isset($user['id'])) {
    die(json_encode(['success' => false, 'message' => 'User not logged in or session expired.']));
}

// Prepare data array for database insertion
$db_arr = [];

foreach ($columns as $column) {
    // Handle timestamps
    if ($column === 'created_at' || $column === 'updated_at') {
        $value = date('Y-m-d H:i:s');
    }
    // Handle created_by
    elseif ($column === 'created_by') {
        $value = $user['id'];
    }
    // Handle password hashing
    elseif ($column === 'password') {
        if (isset($_POST[$column]) && !empty($_POST[$column])) {
            $value = password_hash($_POST[$column], PASSWORD_DEFAULT);
        } else {
            die(json_encode(['success' => false, 'message' => 'Password cannot be empty.']));
        }
    }
    // Handle file uploads
    elseif ($column === 'img') {
        $target_dir = "../uploads/products/";
        $file_data = $_FILES[$column] ?? null;
        if ($file_data) {
            $file_name = $file_data['name'];
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $file_name = 'product-' . time() . '.' . $file_ext;

            // Validate image file
            $check = getimagesize($file_data['tmp_name']);
            if ($check && move_uploaded_file($file_data['tmp_name'], $target_dir . $file_name)) {
                $value = $file_name;
            } else {
                die(json_encode(['success' => false, 'message' => 'Invalid or failed file upload.']));
            }
        } else {
            $value = ''; // Set value to empty if no file was uploaded
        }
    }
    // Handle other columns
    else {
        $value = $_POST[$column] ?? '';
    }

    $db_arr[$column] = $value;
}

// Prepare SQL statement with placeholders
$table_properties = implode(", ", array_keys($db_arr));
$table_placeholders = ':' . implode(", :", array_keys($db_arr));

try {
    // Insert into the database
    $sql = "INSERT INTO $table_name ($table_properties) VALUES ($table_placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($db_arr);

    $response = [
        'success' => true,
        'message' => 'Successfully added to the system'
    ];
} catch (PDOException $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

// Store response in session and redirect
$_SESSION['response'] = $response;
header('Location: ../' . ($_SESSION['redirect_to'] ?? 'index.php'));
exit();
?>
