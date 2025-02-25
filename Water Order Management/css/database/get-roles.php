<?php
require_once('../database/database.php');  // Ensure correct path to the database

// Connect to the database
$database = new Database();
$conn = $database->dbConnection();

// Fetch the roles from the database
$stmt = $conn->prepare("SELECT id, roles FROM role");
$stmt->execute();

$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['status' => 'success', 'roles' => $roles]);
?>
