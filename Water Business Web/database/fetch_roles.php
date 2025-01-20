<?php
include('connection.php');

// Fetch roles from the database
try {
    $stmt = $conn->query('SELECT id, name FROM roles');
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle error
    $roles = [];
}

// Return roles
return $roles;
?>

