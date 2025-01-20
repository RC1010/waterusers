<?php
session_start();
require_once './database/database.php';

$database = new Database();
$conn = $database->dbConnection();

date_default_timezone_set('Asia/Manila');

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to the login page
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user']['id'];
$role_id = $_SESSION['user']['role_id'];

$items_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = $page < 1 ? 1 : $page;
$offset = ($page - 1) * $items_per_page;

$sql = "SELECT c.first_name, c.last_name, c.address, c.number, co.quantity_ordered, co.created_at, co.updated_at, 
        r.roles AS created_by_role, co.id 
        FROM customer c
        JOIN customer_orders co ON c.id = co.customer_id
        JOIN user u ON co.created_by = u.id
        JOIN role r ON u.role_id = r.id
        LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$customer_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_stmt = $conn->query("SELECT COUNT(*) FROM customer_orders");
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

    <div class="container">
        <section class="side-section">
            <div class="side-content">
                <h1 class="section_header">
                    <i class='bx bxs-user'></i>
                    <span>Customer Order List</span>
                </h1>
                <div class="row">
                    <div class="column column-12">
                        <div class="section_content">
                            <div class="user">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Customer</th>
                                            <th>Address</th>
                                            <th>Number</th>
                                            <th>Quantity Ordered</th>
                                            <th>Created By</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($customer_orders as $index => $order): ?>
                                            <tr>
                                                <td><?= $offset + $index + 1 ?></td>
                                                <td><?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></td>
                                                <td><?= htmlspecialchars($order['address']) ?></td>
                                                <td><?= htmlspecialchars($order['number']) ?></td>
                                                <td><?= htmlspecialchars($order['quantity_ordered']) ?></td>
                                                <td><?= htmlspecialchars($order['created_by_role']) ?></td>
                                                <td><?= date('M d, Y h:i:s A', strtotime($order['created_at'])) ?></td>
                                                <td><?= date('M d, Y h:i:s A', strtotime($order['updated_at'])) ?></td>
                                                
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <div class="pagination">
                                    <?php if ($page > 1): ?>
                                        <a href="?page=<?= $page - 1 ?>">&laquo; Previous</a>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <a href="?page=<?= $i ?>" <?= $i == $page ? 'class="active"' : '' ?>><?= $i ?></a>
                                    <?php endfor; ?>

                                    <?php if ($page < $total_pages): ?>
                                        <a href="?page=<?= $page + 1 ?>">Next &raquo;</a>
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
