<?php
session_start();
require_once './database/database.php'; // Include Database class

// Initializing Database Connection
$database = new Database();
$conn = $database->dbConnection(); // Get the PDO connection object

date_default_timezone_set('Asia/Manila');

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to the login page
    header('Location: index.php');
    exit;
}

// Get the user ID and role ID from the session
$user_id = $_SESSION['user']['id'];
$role_id = $_SESSION['user']['role_id'];

// Fetch customers from the database
$customers_sql = "SELECT id, CONCAT(first_name, ' ', last_name) AS customer FROM customer";
$customers_stmt = $conn->prepare($customers_sql);
$customers_stmt->execute();
$customers = $customers_stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle POST requests (from submission)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $quantity_ordered = $_POST['quantity_ordered'];
    $amount = $_POST['amount'];  // New amount field

    // Proceed with database insertion
    $current_timestamp = date('Y-m-d H:i:s');
    $insert_sql = "INSERT INTO customer_orders (customer_id, quantity_ordered, amount, created_by, created_at, updated_at)
               VALUES (:customer_id, :quantity_ordered, :amount, :created_by, :created_at, :updated_at)";

    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bindValue(':customer_id', $customer_id, PDO::PARAM_INT);
    $insert_stmt->bindValue(':quantity_ordered', $quantity_ordered, PDO::PARAM_INT);
    $insert_stmt->bindValue(':amount', $amount, PDO::PARAM_STR);
    $insert_stmt->bindValue(':created_by', $user_id, PDO::PARAM_INT);
    $insert_stmt->bindValue(':created_at', $current_timestamp);
    $insert_stmt->bindValue(':updated_at', $current_timestamp);

    if ($insert_stmt->execute()) {
        $_SESSION['response'] = [
            'success' => true,
            'message' => 'Order added successfully!'
        ];
    } else {
        $_SESSION['response'] = [
            'success' => false,
            'message' => 'Error adding order.'
        ];
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch customer orders with JOIN
$orders_sql = "SELECT co.id AS order_id, c.first_name, c.last_name, c.number, 
                      co.quantity_ordered, co.amount, co.created_at
              FROM customer_orders co
              JOIN customer c ON co.customer_id = c.id";
$orders_stmt = $conn->prepare($orders_sql);
$orders_stmt->execute();
$orders = $orders_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A brief description of the page for SEO">
    <title>Rainy Water - Add-Order</title>
    <link rel="stylesheet" href="./css/add-order.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <div class="header">
        <?php include ("nav.php"); ?>
    </div>

    <!-- HOME SECTION -->
     <div class="container">
        <section class="home-section">
            <div class="home-content">
                <h1 class="section_header">
                    <i class='bx bxs-user-plus'></i>
                    <span>Create New Order</span>
                </h1>

                <!-- Check if user is Admin and show form -->
                 <div id="orderAddFormContainer">
                    
                 <form action="add-order.php" method="POST" enctype="multipart/form-data">
                    <!-- Customer Selection Dropdown -->
                    <div class="appFromInputContainer">
                        <select name="customer_id" id="customer_id" class="input" required>
                            <option value="" disabled selected>Customer</option>
                            <?php 
                            // Fetch customers from the database
                            foreach ($customers as $customer): ?>
                                <option value="<?= $customer['id'] ?>"><?= $customer['customer'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i class="bx bx-list-ul"></i> 
                    </div>

                    <div class="appFromInputContainer">
                        <input type="text" id="quantity_ordered" name="quantity_ordered" class="input" required>
                        <span class="floating-label">Quantity Ordered</span>
                    </div>

                    <div class="appFromInputContainer">
                        <input type="number" id="amount" name="amount" class="input" step="0.01" min="0" required>
                        <span class="floating-label">Amount (₱)</span>
                    </div>


                    <button type="submit" class="appBtn" id="submitBtn" value="Signup">
                        <i class='bx bx-plus'></i>Add Order
                    </button>
                </form>

                    <?php
                        if (isset($_SESSION['response'])) {
                            $response_message = $_SESSION['response']['message'];
                            $is_success = $_SESSION['response']['success'];
                        ?>
                            <div class="responseMessage">
                                <p class="<?= $is_success ? 'responseMessage_success' : 'responseMessage_error' ?>">
                                    <?= htmlspecialchars($response_message, ENT_QUOTES, 'UTF-8') ?>
                                </p>
                            </div>
                        <?php
                            unset($_SESSION['response']);
                        }
                        ?>
                 </div>
            </div>
        </section>
     </div>

<script src="./js/add.js?v=<?= time(); ?>"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWxZxUPnCJA712mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/js/bootstrap-dialog.js" integrity="sha512-AZ+KX5NSCHCQKWBfRX1Ctb+ckjKYL01110faHLPXtGacz34rhXU8KM4t77XXG/Oy9961AeLqB/500KTJfy2WiA==" crossorigin="anonymous"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const customerSelect = document.getElementById('customer_id');
    
    // Listen for change in customer selection
    customerSelect.addEventListener('change', function() {
        const customerId = this.value; // Get the selected customer ID
        console.log('Selected customer ID:', customerId); // Debugging line
        
        if (customerId) {
            // Fetch customer details from the PHP script
            fetch(`database/get-customer-details.php?customer_id=${customerId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Fetched customer data:', data); // Debugging line
                    
                    if (data.first_name) {
                            document.getElementById('first_name').value = data.first_name;
                            document.getElementById('last_name').value = data.last_name;
                            document.getElementById('address').value = data.address;
                            document.getElementById('number').value = data.number;
                        } else {
                            alert('Customer details not found.');
                        }

                })
        }
    });
});
</script>

</body>
</html>
