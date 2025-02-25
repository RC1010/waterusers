<?php
session_start();
require_once './database/database.php'; // Include Users class

// Initialize Database connection
$database = new Database();
$conn = $database->dbConnection(); // Get the PDO connection object


date_default_timezone_set('Asia/manila'); // Replace with your desired timezone

// Fetch roles from the database
$roles_query = "SELECT * FROM role";
$result = $conn->query($roles_query);
$roles = $result->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows as an associative array

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to the login page
    header('Location: index.php');
    exit;
}
// Get the user ID and role ID from the session
$user_id = $_SESSION['user']['id'];
$role_id = $_SESSION['user']['role_id'];

// Check if the logged-in user is an Admin (role_id = 1)
if ($role_id != 1) {
    die('You do not have permission to access this page.');
}

// Handle POST requests (form submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data and insert new user into the database
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role_id = $_POST['role_id']; // This will be chosen by Admin

    // Get the current timestamp for created_at and updated_at
    $current_timestamp = date('Y-m-d H:i:s');

    // Insert SQL query with created_at and updated_at included
    $insert_sql = "INSERT INTO user (first_name, last_name, email, password, role_id, created_by, created_at, updated_at) 
                   VALUES (:first_name, :last_name, :email, :password, :role_id, :created_by, :created_at, :updated_at)";

    // Prepare the insert statement
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bindValue(':first_name', $first_name);
    $insert_stmt->bindValue(':last_name', $last_name);
    $insert_stmt->bindValue(':email', $email);
    $insert_stmt->bindValue(':password', $password);
    $insert_stmt->bindValue(':role_id', $role_id);
    $insert_stmt->bindValue(':created_by', $user_id); // Set the "created_by" field to the logged-in user's ID
    $insert_stmt->bindValue(':created_at', $current_timestamp); // Set created_at to the current timestamp
    $insert_stmt->bindValue(':updated_at', $current_timestamp); // Set updated_at to the current timestamp

    // Attempt to execute the insert statement
    if ($insert_stmt->execute()) {
        // Store success response in session
        $_SESSION['response'] = [
            'success' => true,
            'message' => 'User added successfully!'
        ];
    } else {
        // Store error response in session
        $_SESSION['response'] = [
            'success' => false,
            'message' => 'Error adding user!'
        ];
    }

    // Redirect to the same page or a specific page to display the response
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
    <title>Rainy Water - Add-User</title>
    <link rel="stylesheet" href="./css/add-user.css">
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
                    <span>Create New User</span>
                </h1>

                <!-- Check if user is Admin and show form -->
                    <div id="productAddFormContainer">
                        <form action="add-user.php" method="POST" enctype="multipart/form-data">
                            <div class="appFromInputContainer">
                                <input type="text" id="first_name" name="first_name" class="input" required>
                                <span class="floating-label">Firstname</span>
                            </div>
                            <div class="appFromInputContainer">
                                <input type="text" id="last_name" name="last_name" class="input" required>
                                <span class="floating-label">Lastname</span>
                            </div>
                            <div class="appFromInputContainer">
                                <input type="email" id="email" name="email" class="input" required>
                                <span class="floating-label">Email</span>
                            </div>
                            <div class="appFromInputContainer">
                                <input type="password" name="password" class="password" id="password" required>
                                <i class='bx bx-hide eye-icon'></i>
                                <span class="floating-label">Password</span>
                            </div>
                            <div class="appFromInputContainer">
                                <input type="password" name="confirm_password" class="password" id="confirm_password" required>
                                <i class='bx bx-hide eye-icon'></i>
                                <span class="floating-label">Confirm Password</span>
                            </div>

                            <!-- Role Selection Dropdown -->
                            <div class="appFromInputContainer">
                                <select name="role_id" class="input" required>
                                    <option value="" disabled selected>Role</option>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?= $role['id'] ?>"><?= $role['roles'] ?></option>
                                    <?php endforeach; ?>

                                </select>
                                <i class="bx bx-list-ul"></i> 
                            </div>

                            <button type="submit" class="appBtn" id="submitBtn" value="Signup">
                                <i class='bx bx-plus'></i>Add User
                            </button>

                            <div class="pass-err" id="password-error" style="color: red; display: none;">
                                Passwords do not match.
                            </div>
                        </form>

                        <?php 
                            if (isset($_SESSION['response'])) {
                                $response_message = $_SESSION['response']['message'];
                                $is_success = $_SESSION['response']['success'];     
                            ?>
                                <div class="responseMessage">
                                    <p class="<?= $is_success ? 'responseMessage__success' : 'responseMessage__error' ?>">
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
        document.getElementById('submitBtn').addEventListener('click', function(event) {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                event.preventDefault(); // Prevent form submission
                document.getElementById('password-error').style.display = 'block';
            } else {
                document.getElementById('password-error').style.display = 'none';
            }
        });
    </script>
</body>
</html>

