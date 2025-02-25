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

$user_id = $_SESSION['user']['id'];
$role_id = $_SESSION['user']['role_id'];

if ($role_id != 1) {
    die('You do not have permission to access this page.');
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
                                            <th>Action</th>
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
                                                <td>
                                                    <div class="button">
                                                        <a href="#" class="updateCustomer" pid="<?= $customer['id'] ?>"> <i class="bx bx-edit-alt"></i></a>
                                                        <a href="#" class="deleteCustomer" data-id="<?= $customer['id'] ?>" data-name="<?= $customer['first_name'] ?>"> 
                                                            <i class="bx bx-trash-alt"></i>
                                                        </a>
                                                    </div>
                                                </td>
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

<!-- Edit Customer Modal -->
<div id="editCustomerModal" class="modal-background">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Customer</h3>
        </div>
        <form id="editCustomerForm" method="POST" action="./database/update-customer.php">
            <input type="hidden" name="customer_id" id="editCustomerId">
            <div class="form-group">
                <label for="editFirstName">First Name</label>
                <input type="text" name="first_name" id="editFirstName" required>
            </div>
            <div class="form-group">
                <label for="editLastName">Last Name</label>
                <input type="text" name="last_name" id="editLastName" required>
            </div>
            <div class="form-group">
                <label for="editAddress">Address</label>
                <input type="text" name="address" id="editAddress" required>
            </div>
            <div class="form-group">
                <label for="editNumber">Number</label>
                <input type="text" name="number" id="editNumber" required>
            </div>
            
            <button type="submit" class="save-btn">Save Changes</button>
            <button type="button" class="close-modal">Cancel</button>
        </form>
    </div>
</div>

<!-- Delete Customer Modal -->
<div id="deleteCustomerModal" class="modal-background">
    <div class="modal-content">
        <h3>Are you sure you want to delete this customer?</h3>
        <p>Customer: <span id="deleteCustomerName"></span></p>
        <form id="deleteCustomerForm" method="POST" action="./database/delete-customer.php">
            <input type="hidden" name="customer_id" id="deleteCustomerId">
            <button type="submit" class="delete-btn">Yes</button>
            <button type="button" class="close-modal">No</button>
        </form>
    </div>
</div>

<!-- Success/Error Popup -->
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
document.querySelectorAll('.updateCustomer').forEach(function(button) {
    button.addEventListener('click', function(event) {
        event.preventDefault();

        var customerId = this.getAttribute('pid');
        fetch('./database/get-customer.php', {
            method: 'POST',
            body: new URLSearchParams({ customer_id: customerId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('editCustomerId').value = data.customer.id;
                document.getElementById('editFirstName').value = data.customer.first_name;
                document.getElementById('editLastName').value = data.customer.last_name;
                document.getElementById('editAddress').value = data.customer.address;
                document.getElementById('editNumber').value = data.customer.number;
                document.getElementById('editCustomerModal').style.display = 'flex';
            } else {
                alert('Error fetching user data.');
            }
        });
    });
});

// Handle Delete Customer Modal
document.querySelectorAll('.deleteCustomer').forEach(function(button) {
    button.addEventListener('click', function(event) {
        event.preventDefault();

        var customerId = this.getAttribute('data-id'); // Ensure this is customerId, not userId
        var customerName = this.getAttribute('data-name');
        document.getElementById('deleteCustomerId').value = customerId;
        document.getElementById('deleteCustomerName').textContent = customerName;
        document.getElementById('deleteCustomerModal').style.display = 'flex';
    });
});


// Close the modals when clicking the close button or background
document.querySelectorAll('.close-modal').forEach(function(closeBtn) {
    closeBtn.addEventListener('click', function() {
        this.closest('.modal-background').style.display = 'none';
    });
});

// Close the modals if user clicks outside the modal content
document.querySelectorAll('.modal-background').forEach(function(modal) {
    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});

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

    // Reload the page after showing the popup
    setTimeout(function() {
        location.reload();
    }, 1000);
}

// Handle Edit User Form submission
const editForm = document.getElementById('editCustomerForm');
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
                // After success, show success message and reload
                showPopup('Customer edited successfully!', 'success');
            } else {
                showPopup('Failed to edit Customer. Please try again.', 'failure');
            }
        })
        .catch(error => {
            showPopup('Error: ' + error, 'failure');
        });
    });
}

// Handle Delete User Form submission
const deleteForm = document.getElementById('deleteCustomerForm');
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
                // After success, show success message and reload
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
});

    </script>
    
</body>
</html>
