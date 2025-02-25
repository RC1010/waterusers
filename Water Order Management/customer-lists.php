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

$items_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = $page < 1 ? 1 : $page;
$offset = ($page - 1) * $items_per_page;

$sql = "
    SELECT 
        customer.*, 
        CONCAT(user.first_name, ' ', user.last_name, ' (', role.roles, ')') AS created_by_user_role
    FROM customer
    LEFT JOIN user ON customer.created_by = user.id
    LEFT JOIN role ON user.role_id = role.id
    LIMIT :limit OFFSET :offset
";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);


$total_stmt = $conn->query("SELECT COUNT(*) FROM customer");
$total_records = $total_stmt->fetchColumn();
$total_pages = ceil($total_records / $items_per_page);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A brief description of the page for SEO">
    <title>Rainy Water - Edit-Customer</title>    
    <!-- Bootstrap 5 CSS
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">-->
    <link rel="stylesheet" href="./css/view-user.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-dialog/dist/css/bootstrap-dialog.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="./css/nav.css">
</head>
<body>
    <div class="header">
        <?php include ("nav.php"); ?> 
    </div>

    <!-- HOME SECTION -->
    <div class="container">

        <!-- SIDE SECTION -->
        <section class="side-section">
            <div class="side-content">
                <h1 class="section_header">
                    <i class='bx bxs-user'></i>
                    <span>Customer List</span>
                </h1>
                <div class="row">
                    <div class="column column-12">
                        <div class="section_content">
                            <div class="user">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Address</th>
                                            <th>Number</th>
                                            <th>Created By</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($customers as $index => $customer){ ?>
                                            <tr>
                                                <td><?= $offset + $index + 1 ?></td>
                                                <!--<td class="image">
                                                    <img class="userImages" src="uploads/users/<?= $user['img'] ?>" alt="" />
                                                </td>-->
                                                <td class="full_name"><?= $customer['first_name'] . ' ' . $customer['last_name'] ?></td>
                                                <td class="address"><?= $customer['address'] ?></td>
                                                <td class="number"><?= $customer['number'] ?></td>
                                                <td><?= !empty($customer['created_by_user_role']) ? htmlspecialchars($customer['created_by_user_role']) : 'N/A' ?></td>
                                                <td><?= date('M d, Y h:i:s A', strtotime($customer['created_at'])) ?></td>
                                                <td><?= date('M d, Y h:i:s A', strtotime($customer['updated_at'])) ?></td>
                                                
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <p class="userCount"><?= $total_records ?> Customer </p>

                                <!-- Pagination controls -->
                                <div class="pagination">
                                    <?php if ($page > 1): ?>
                                        <a href="?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <a href="?page=<?php echo $i; ?>" <?php echo ($i == $page) ? 'class="active"' : ''; ?>>
                                            <?php echo $i; ?>
                                        </a>
                                    <?php endfor; ?>

                                    <?php if ($page < $total_pages): ?>
                                        <a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>