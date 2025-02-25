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

// Fetch roles for the dropdown
$roles_stmt = $conn->query("SELECT * FROM role");
$roles = $roles_stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A brief description of the page for SEO">
    <title>Rainy Water - Edit-User</title>    
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
                                            <th>Action</th>
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
                                                    // Check if the current user matches the logged-in user
                                                    if ($user['id'] == $_SESSION['user']['id']) {
                                                        echo '<span class="status active">Active</span>';
                                                    } elseif (isset($user['status']) && $user['status'] == 'active') {
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
                                                <td>
                                                    <div class="button">
                                                        <a href="#" class="updateUser" pid="<?= $user['id'] ?>"> <i class="bx bx-edit-alt"></i></a>
                                                        <a href="#" class="deleteUser" data-id="<?= $user['id'] ?>" data-name="<?= $user['first_name'] ?>"> 
                                                            <i class="bx bx-trash-alt"></i>
                                                        </a>
                                                    </div>
                                                </td>
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

<!-- Edit User Modal -->
<div id="editUserModal" class="modal-background">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit User</h3>
        </div>
        <form id="editUserForm" method="POST" action="./database/update-user.php">
            <input type="hidden" name="user_id" id="editUserId">
            <div class="form-group">
                <label for="editFirstName">First Name</label>
                <input type="text" name="first_name" id="editFirstName" required>
            </div>
            <div class="form-group">
                <label for="editLastName">Last Name</label>
                <input type="text" name="last_name" id="editLastName" required>
            </div>
            <div class="form-group">
                <label for="editEmail">Email</label>
                <input type="email" name="email" id="editEmail" required>
            </div>
            <div class="form-group">
                <label for="editRole">Role</label>
                <select name="role_id" id="editRole" required>
                    <!-- Roles will be populated dynamically -->
                </select>
            </div>
            <button type="submit" class="save-btn">Save Changes</button>
            <button type="button" class="close-modal">Cancel</button>
        </form>
    </div>
</div>

<!-- Delete User Modal -->
<div id="deleteUserModal" class="modal-background">
    <div class="modal-content">
        <h3>Are you sure you want to delete this user?</h3>
        <p>User: <span id="deleteUserName"></span></p>
        <form id="deleteUserForm" method="POST" action="./database/delete-user.php">
            <input type="hidden" name="user_id" id="deleteUserId">
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
} else {
    // If status or action is not present, you can either skip or handle differently
    console.log("No status or action parameter found in the URL.");
}


// Fetch the roles when the page loads
fetch('./database/get-roles.php')
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const roleSelect = document.getElementById('editRole');
            data.roles.forEach(role => {
                const option = document.createElement('option');
                option.value = role.id;
                option.textContent = role.roles;
                roleSelect.appendChild(option);
            });
        } else {
            console.error('Failed to fetch roles');
        }
    });

// Handle Edit User Modal
document.querySelectorAll('.updateUser').forEach(function(button) {
    button.addEventListener('click', function(event) {
        event.preventDefault();

        var userId = this.getAttribute('pid');
        fetch('./database/get-user.php', {
            method: 'POST',
            body: new URLSearchParams({ user_id: userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('editUserId').value = data.user.id;
                document.getElementById('editFirstName').value = data.user.first_name;
                document.getElementById('editLastName').value = data.user.last_name;
                document.getElementById('editEmail').value = data.user.email;
                document.getElementById('editRole').value = data.user.role_id;
                document.getElementById('editUserModal').style.display = 'flex';
            } else {
                alert('Error fetching user data.');
            }
        });
    });
});

// Handle Delete User Modal
document.querySelectorAll('.deleteUser').forEach(function(button) {
    button.addEventListener('click', function(event) {
        event.preventDefault();

        var userId = this.getAttribute('data-id');
        var userName = this.getAttribute('data-name');
        document.getElementById('deleteUserId').value = userId;
        document.getElementById('deleteUserName').textContent = userName;
        document.getElementById('deleteUserModal').style.display = 'flex';
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
const editForm = document.getElementById('editUserForm');
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
                showPopup('User edited successfully!', 'success');
            } else {
                showPopup('Failed to edit user. Please try again.', 'failure');
            }
        })
        .catch(error => {
            showPopup('Error: ' + error, 'failure');
        });
    });
}

// Handle Delete User Form submission
const deleteForm = document.getElementById('deleteUserForm');
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
                showPopup('User deleted successfully!', 'success');
            } else {
                showPopup('Failed to delete user. Please try again.', 'failure');
            }
        })
        .catch(error => {
            showPopup('Error: ' + error, 'failure');
        });
    });
}
});

</script>
    <!-- SweetAlert 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.deleteUser');
            const updateButtons = document.querySelectorAll('.updateUser');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const userId = this.getAttribute('data-id');
                    const userName = this.getAttribute('data-name');
                    document.getElementById('deleteUserName').textContent = userName;
                    document.getElementById('deleteUserId').value = userId;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to delete ${userName}. This action cannot be undone.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('deleteUserForm').submit();
                        }
                    });
                });
            });

            updateButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const userId = this.getAttribute('pid');
                    const row = this.closest('tr');
                    const firstName = row.querySelector('.first_name').textContent;
                    const email = row.querySelector('.email').textContent;
                    const role = row.querySelector('.role_id').textContent;

                    document.getElementById('editUserId').value = userId;
                    document.getElementById('editFirstName').value = firstName;
                    document.getElementById('editEmail').value = email;
                    document.getElementById('editRole').value = role;

                    new bootstrap.Modal(document.getElementById('editUserModal')).show();
                });
            });
        });
    </script>

     Bootstrap JS 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>-->
</body>
</html>
