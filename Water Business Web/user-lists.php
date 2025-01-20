<?php
session_start();
require_once './database/database.php';

$database = new Database();
$conn = $database->dbConnection();

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
        u.*, 
        r.roles AS created_by_role,
        ur.roles AS user_role
    FROM user u
    LEFT JOIN user created_by_user ON u.created_by = created_by_user.id
    LEFT JOIN role r ON created_by_user.role_id = r.id
    LEFT JOIN role ur ON u.role_id = ur.id
    LIMIT :limit OFFSET :offset
";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);


$total_stmt = $conn->query("SELECT COUNT(*) FROM user");
$total_records = $total_stmt->fetchColumn();
$total_pages = ceil($total_records / $items_per_page);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A brief description of the page for SEO">
    <title>Rainy Water - Add-User</title>    
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
                    <span>User List</span>
                </h1>
                <div class="row">
                    <div class="column column-12">
                        <div class="section_content">
                            <div class="user">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Status</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Created By</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($users as $index => $user){ ?>
                                            <tr>
                                                <td><?= $offset + $index + 1 ?></td>
                                                <!--<td class="image">
                                                    <img class="userImages" src="uploads/users/<?= $user['img'] ?>" alt="" />
                                                </td>-->
                                                <td><?php
                                                    // Check if the 'status' key exists and display accordingly
                                                    if (isset($user['status']) && $user['status'] == 'active') {
                                                        echo '<span class="status active">Active</span>';
                                                    } else {
                                                        echo '<span class="status inactive">Inactive</span>';
                                                    }
                                                ?></td>
                                                <td class="full_name"><?= $user['first_name'] . ' ' . $user['last_name'] ?></td>
                                                <td class="email"><?= $user['email'] ?></td>
                                                <td class="user"><?= $user['user_role'] ?></td>
                                                <td><?= !empty($user['created_by']) ? htmlspecialchars($user['created_by_role']) : 'N/A' ?></td>
                                                <td><?= date('M d, Y h:i:s A', strtotime($user['created_at'])) ?></td>
                                                <td><?= date('M d, Y h:i:s A', strtotime($user['updated_at'])) ?></td>
                                                
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <p class="userCount"><?= $total_records ?> Users </p>

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
