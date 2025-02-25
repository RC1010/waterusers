<?php
session_start();

require_once './database/database.php';

$database = new Database();
$conn = $database->dbConnection();

date_default_timezone_set('Asia/Manila'); // Adjust to your timezone

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to the login page
    header('Location: index.php');
    exit;
}

// Query counts
try {
    // Count customers
    $customerQuery = $conn->query("SELECT COUNT(*) AS count FROM customer");
    $customerCount = $customerQuery->fetch(PDO::FETCH_ASSOC)['count'];

    // Count customer orders
    $orderQuery = $conn->query("SELECT COUNT(*) AS count FROM customer_orders");
    $orderCount = $orderQuery->fetch(PDO::FETCH_ASSOC)['count'];

    // Count users
    $userQuery = $conn->query("SELECT COUNT(*) AS count FROM user");
    $userCount = $userQuery->fetch(PDO::FETCH_ASSOC)['count'];

    // Count roles
    $roleQuery = $conn->query("SELECT COUNT(*) AS count FROM role");
    $roleCount = $roleQuery->fetch(PDO::FETCH_ASSOC)['count'];

    // 🔹 Get total amount of all orders
    $amountQuery = $conn->query("SELECT SUM(amount) AS total_amount FROM customer_orders");
    $totalAmount = $amountQuery->fetch(PDO::FETCH_ASSOC)['total_amount'] ?? 0; // Handle NULL case

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A brief description of the page for SEO">
    <title>Rainy Water</title>
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div>
        <?php include("nav.php"); ?> 
    </div>

    <main class="main-section">    
        <div class="main-content">
            <div class="user-count">
                <div class="userCount">Users: <?= $userCount; ?></div>
            </div>
            <div class="role-count">
                <div class="roleCount">Roles: <?= $roleCount; ?></div>
            </div>
            <div class="customer-count">
                <div class="customerCount">Customers: <?= $customerCount; ?></div>
            </div>
            <div class="order-count">
                <div class="orderCount">Orders: <?= $orderCount; ?></div>
            </div>
        </div>  
        <hr>
    </main>

    <section>
    <div class="graph">
        <?php include ("graph/graph.php"); ?> 
    </div>
    </section>

    <side class="side-main">
        <div class="section-content">
            <div class="amount-total">
                <div class="amountTotal">Total Amount:<br>₱<?= number_format($totalAmount, 2); ?></div>
            </div>
        </div>
    </side>
    <hr>

    <script src="./script.js?v=<?= time(); ?>"></script>
</body>
</html>

