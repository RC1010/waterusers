<?php
session_start();
require_once './database/connection.php';

$conn = new Connection();
$error_message = '';

if (isset($_SESSION['user'])) {
    // Set the user_id in session if it's not already set
    if (!isset($_SESSION['user_id']) && isset($_SESSION['user']['id'])) {
        $_SESSION['user_id'] = $_SESSION['user']['id'];
    }
    header('Location: main.php');
    exit(); // Make sure to exit after sending a header for redirection
}

// Ensure user_id is available in session before using it
if (isset($_SESSION['user']['id'])) {
    $user_id = $_SESSION['user']['id'];
    $pdo = $conn->getConnection(); // Get the PDO connection
    $stmt = $pdo->prepare("UPDATE user SET status = 'active' WHERE id = :id");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
}

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user by email
    $user = $conn->loginUser($email, $password);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;

            // Update user status to active
            $pdo = $conn->getConnection(); // Get the PDO connection
            $stmt = $pdo->prepare("UPDATE user SET status = 'active' WHERE id = :id");
            $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
            $stmt->execute();

            header('Location: main.php');
            exit(); // Redirect to the main page upon successful login
        } else {
            $error_message = 'Email or password is incorrect';
        }
    } else {
        $error_message = 'Email or password is incorrect';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Water Web" content="A brief description of the page for SEO">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Rainy Water</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
          integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<header>
    <h1>Rainy water</h1>
    <nav>
        <ul>
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact us</a></li>
        </ul>
    </nav>
</header>
<main>
    <section id="home">
        <img src="./css/images/water_cn.png" class="water-cn-img" alt="Water Cn">
        <img src="./css/images/water_bottle_1.png" class="water-bottle-img" alt="Water bttl">
        <h2>Welcome to Rainy Water!</h2>
        <p>Welcome to Rainy Water, your trusted source for premium hydration solutions. At Rainy Water, we are dedicated to providing you with the purest, freshest water to keep you healthy and refreshed.</p>
        <div class="container">
            <button class="button" role="button" id="loginButton">Login Now</button>
        </div>
        <p>Join us in our mission to stay hydrated and protect our planet, one bottle at a time.</p>
        <div class="modal" id="modal">
            <div class="modal-content">
                <span class="close-button" id="closeButton">×</span>
                <div class="form login">
                    <div class="form-content">
                        <?php if (!empty($error_message)): ?>
                            <div class="error" id="errorMessage">
                                <h3><strong>ERROR:</strong></h3> <?= htmlspecialchars($error_message) ?>
                            </div>
                        <?php endif; ?>
                        <header>Login</header>
                        <form action="index.php" method="POST">
                            <div class="field input-field">
                                <input type="email" name="email" class="input" required>
                                <span class="floating-label">Email</span>
                            </div>
                            <div class="field input-field">
                                <input type="password" name="password" class="password" required>
                                <i class='bx bx-hide eye-icon'></i>
                                <span class="floating-label">Password</span>
                            </div>
                            <div class="form-link">
                                <a href="#" class="forgot-pass">Forgot password?</a>
                            </div>
                            <div class="field button-field">
                                <button class="button2">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <hr>
    <section id="about">
        <h2>About Us</h2>
        <p>Our Company is apple bottom jeans boots with a fur, the whole club is looking at her. Lorem ipsum dolor, sit amet consectetur adipisicing elit. Officia repellat cupiditate tenetur nisi aliquid reprehenderit, perferendis quasi quis aut ipsa iusto nostrum eveniet a ratione voluptatibus ex vero quibusdam harum.</p>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora quod quia eius labore quae alias culpa deserunt sit enim expedita illo amet perspiciatis ab est reiciendis aliquam, velit incidunt sapiente?</p>
    </section>
    <hr>
    <section id="contact">
        <h2>Contact Us</h2>
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Facilis fugit officia illum magnam et consequatur explicabo eveniet earum atque asperiores, inventore, ipsum temporibus? Nostrum recusandae quaerat non similique, voluptate rerum.</p>
        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Consequatur, quibusdam. Dolor ipsa distinctio molestias ducimus rem pariatur ea, deleniti culpa consequuntur necessitatibus eligendi saepe non sequi excepturi ex, eaque nesciunt.</p>
        <div class="container">
            <button class="button" onclick="window.location.href='mailto:rogelioccuya@gmail.com'">Email Us</button>
        </div>
    </section>
    <hr>
</main>
<footer>
    <p>© All rights reserved</p>
</footer>
<script src="./js/script.js"></script>
</body>
</html>
