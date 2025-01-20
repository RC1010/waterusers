<?php
session_start();
require_once './database/database.php'; // Include Users Class

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

// Handle POST requests (from submission)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $number = $_POST['number'];

    // Ensure the number starts with +63
    if (!str_starts_with($number, '+63')) {
        $number = '+63' . ltrim($number, '0');
    }

    // Proceed with database insertion
    $current_timestamp = date('Y-m-d H:i:s');
    $insert_sql = "INSERT INTO customer (first_name, last_name, address, number, created_by, created_at, updated_at)
              VALUES (:first_name, :last_name, :address, :number, :created_by, :created_at, :updated_at)";

    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bindValue(':first_name', $first_name);
    $insert_stmt->bindValue(':last_name', $last_name);
    $insert_stmt->bindValue(':address', $address);
    $insert_stmt->bindValue(':number', $number);
    $insert_stmt->bindValue(':created_by', $user_id);
    $insert_stmt->bindValue(':created_at', $current_timestamp);
    $insert_stmt->bindValue(':updated_at', $current_timestamp);

    if ($insert_stmt->execute()) {
        $_SESSION['response'] = [
            'success' => true,
            'message' => 'Customer added successfully!'
        ];
    } else {
        $_SESSION['response'] = [
            'success' => false,
            'message' => 'Error adding Customer.'
        ];
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A brief description of the page for SEO">
    <title>Rainy Water - Add-Customer</title>
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
                    <span>Add Customer</span>
                </h1>

                <!-- Check if user is Admin and show form -->
                 <div id="orderAddFormContainer">
                    <form action="add-customer.php" method="POST" enctype="multipart/form-data">
                        <div class="appFromInputContainer">
                            <input type="text" id="first_name" name="first_name" class="input" required>
                            <span class="floating-label">Firstname</span>
                        </div>
                        <div class="appFromInputContainer">
                            <input type="text" id="last_name" name="last_name" class="input" required>
                            <span class="floating-label">Laststname</span>
                        </div>
                        <div class="appFromInputContainer">
                            <input type="text" id="address" name="address" class="input" required>
                            <span class="floating-label">Address</span>
                        </div>
                        <div class="appFromInputContainer">
                            <input type="text" id="number" name="number" class="input" required>
                            <span class="floating-label">Contact Number</span>
                        </div>
                        <button type="submit" class="appBtn" id="submitBtn" value="Signup">
                            <i class='bx bx-plus'></i>Add Customer
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
document.addEventListener("DOMContentLoaded", () => {
    const numberInput = document.getElementById("number");

    // Ensure the input starts with +63
    numberInput.addEventListener("focus", () => {
        if (!numberInput.value.startsWith("+63")) {
            numberInput.value = "+63";
        }
    });

    // Restrict input to numbers and limit to 12 characters
    numberInput.addEventListener("input", (event) => {
        let value = numberInput.value;

        // Ensure it starts with +63
        if (!value.startsWith("+63")) {
            value = "+63";
        }

        // Remove invalid characters and enforce length limit
        value = value.replace(/[^+\d]/g, "").substring(0, 13);

        numberInput.value = value;
    });
});

</script>

</body>
</html>