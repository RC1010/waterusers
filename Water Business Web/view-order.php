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

if ($role_id != 1) {
    die('You do not have permission to access this page.');
}

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
                                            <th>Action</th>
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
                                                <td>
                                                    <div class="button">
                                                        <a href="#" class="updateOrder" pid="<?= $order['id'] ?>"> <i class="bx bx-edit-alt"></i></a>
                                                        <a href="#" class="deleteOrder" data-id="<?= $order['id'] ?>" data-name="<?= $order['quantity_ordered'] ?>"> 
                                                            <i class="bx bx-trash-alt"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <p class="userCount"><?= $total_records ?> Orders </p>
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

    <!-- Modals and Popup -->
    <div id="editOrderModal" class="modal-background">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Quantity</h3>
        </div>
            <form id="editOrderForm" method="POST" action="./database/update-order.php">
                <input type="hidden" name="order_id" id="editOrderId">
                <div class="form-group">
                    <label for="editQuantity">Quantity Ordered</label>
                    <input type="number" name="quantity_ordered" id="editQuantity" required>
                </div>
                <button type="submit" class="save-btn">Save Changes</button>
                <button type="button" class="close-modal">Cancel</button>
            </form>
        </div>
    </div>


    <div id="deleteOrderModal" class="modal-background">
        <div class="modal-content">
            <h3>Are you sure you want to delete this order?</h3>
            <p>Order Quantity: <span id="deleteOrderQuantity"></span></p>
            <form id="deleteOrderForm" method="POST" action="./database/delete-order.php">
                <input type="hidden" name="order_id" id="deleteOrderId">
                <button type="submit" class="delete-btn">Yes</button>
                <button type="button" class="close-modal">No</button>
            </form>
        </div>
    </div>

    <div id="popupMessage" class="popup-message">
        <div class="popup-content">
            <span id="popupMessageClose" class="popup-close">&times;</span>
            <p id="popupMessageText"></p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Function to get URL parameter value by name
    function getUrlParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    // Show Popup based on the status from the URL
    const status = getUrlParameter('status');
    const action = getUrlParameter('action');
    if (status && action) {
        if (status === 'success') {
            showPopup(`${action.charAt(0).toUpperCase() + action.slice(1)} successfully!`, 'success');
        } else {
            showPopup(`Failed to ${action}. Please try again.`, 'failure');
        }
    }

    // Handle Edit Customer Modal
    document.querySelectorAll('.updateOrder').forEach(function(button) {
    button.addEventListener('click', function(event) {
        event.preventDefault();

        const orderId = this.getAttribute('pid');
        fetch('./database/get-order.php', {
            method: 'POST',
            body: new URLSearchParams({ order_id: orderId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Only load the quantity into the modal
                document.getElementById('editOrderId').value = data.order.id;
                document.getElementById('editQuantity').value = data.order.quantity_ordered;
                
                // Show only the quantity edit modal
                document.getElementById('editOrderModal').style.display = 'flex';
            } else {
                alert('Error fetching order data.');
            }
        });
    });
});


    // Handle Delete Order Modal
    document.querySelectorAll('.deleteOrder').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            const orderId = this.getAttribute('data-id');
            const orderQuantity = this.getAttribute('data-name');

            document.getElementById('deleteOrderId').value = orderId;
            document.getElementById('deleteOrderQuantity').textContent = orderQuantity;
            document.getElementById('deleteOrderModal').style.display = 'flex';
        });
    });

    // Close Modal
    document.querySelectorAll('.close-modal').forEach(function(button) {
        button.addEventListener('click', function() {
            this.closest('.modal-background').style.display = 'none';
        });
    });

    // Handle Edit User Form submission
    const editForm = document.getElementById('editOrderForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();

            fetch(editForm.action, {
                method: 'POST',
                body: new FormData(editForm)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showPopup('Customer Order edited successfully!', 'success');
                } else {
                    showPopup('Failed to edit Customer Order. Please try again.', 'failure');
                }
            })
            .catch(error => {
                showPopup('Error: ' + error, 'failure');
            });
        });
    }

    // Handle Delete User Form submission
    const deleteForm = document.getElementById('deleteOrderForm');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();

            fetch(deleteForm.action, {
                method: 'POST',
                body: new FormData(deleteForm)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showPopup('Customer deleted successfully!', 'success');
                } else {
                    showPopup('Failed to delete Customer. Please try again.', 'failure');
                }
            })
            .catch(error => {
                showPopup('Error: ' + error, 'failure');
            });
        });
    }

    // Function to show the popup message
    function showPopup(message, type) {
        const popup = document.getElementById('popupMessage');
        const popupText = document.getElementById('popupMessageText');
        const popupClose = document.getElementById('popupMessageClose');

        popupText.textContent = message;

        if (type === 'success') {
            popup.classList.add('success');
            popup.classList.remove('error');
        } else {
            popup.classList.add('error');
            popup.classList.remove('success');
        }

        popup.style.display = 'block';

        popupClose.addEventListener('click', function() {
            popup.style.display = 'none';
        });

        // Automatically hide popup after 2 seconds
        setTimeout(function() {
            popup.style.display = 'none';
        }, 2000);

        // Reload the page after the popup disappears
        setTimeout(function() {
            location.reload();
        }, 2200);  // Slight delay after popup disappears
    }
});

    </script>
</body>
</html>
