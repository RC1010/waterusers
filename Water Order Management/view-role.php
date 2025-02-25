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

$sql = "SELECT r.id, r.roles, r.description, r.created_at, r.updated_at, u.first_name, u.last_name 
        FROM role r
        LEFT JOIN user u ON r.created_by = u.id
        LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_stmt = $conn->query("SELECT COUNT(*) FROM role");
$total_records = $total_stmt->fetchColumn();
$total_pages = ceil($total_records / $items_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A brief description of the page for SEO">
    <title>Rainy Water - Edit Roles</title>
    <link rel="stylesheet" href="./css/view-user.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-dialog/dist/css/bootstrap-dialog.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
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
                    <span>Role Management</span>
                </h1>
                <div class="row">
                    <div class="column column-12">
                        <div class="section_content">
                            <div class="user">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Role</th>
                                            <th>Description</th>
                                            <th>Created By</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($roles as $index => $role): ?>
                                            <tr>
                                                <td><?= $offset + $index + 1 ?></td>
                                                <td><?= htmlspecialchars($role['roles']) ?></td>
                                                <td><?= htmlspecialchars($role['description']) ?></td>
                                                <td><?= htmlspecialchars($role['first_name'] . ' ' . $role['last_name']) ?></td>
                                                <td><?= date('M d, Y h:i:s A', strtotime($role['created_at'])) ?></td>
                                                <td><?= date('M d, Y h:i:s A', strtotime($role['updated_at'])) ?></td>
                                                <td>
                                                    <div class="button">
                                                        <a href="#" class="updateRole" pid="<?= $role['id'] ?>"> <i class="bx bx-edit-alt"></i></a>
                                                        <a href="#" class="deleteRole" data-id="<?= $role['id'] ?>" data-name="<?= $role['roles'] ?>"> 
                                                            <i class="bx bx-trash-alt"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <p class="userCount"><?= $total_records ?> Roles</p>
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

    <!-- Edit Role Modal -->
    <div id="editRoleModal" class="modal-background">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Role</h3>
            </div>
            <form id="editRoleForm" method="POST" action="./database/update-role.php">
                <input type="hidden" name="role_id" id="editRoleId">
                <div class="form-group">
                    <label for="editRoleName">Role Name</label>
                    <input type="text" name="role_name" id="editRoleName" required>
                </div>
                <div class="form-group">
                    <label for="editRoleDescription">Description</label>
                    <input type="text" name="role_description" id="editRoleDescription" required>
                </div>
                <button type="submit" class="save-btn">Save Changes</button>
                <button type="button" class="close-modal">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Delete Role Modal -->
    <div id="deleteRoleModal" class="modal-background">
        <div class="modal-content">
            <h3>Are you sure you want to delete this role?</h3>
            <p>Role: <span id="deleteRoleName"></span></p>
            <form id="deleteRoleForm" method="POST" action="./database/delete-role.php">
                <input type="hidden" name="role_id" id="deleteRoleId">
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

    // Handle Edit Role Modal
    document.querySelectorAll('.updateRole').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            const roleId = this.getAttribute('pid');
            fetch('./database/get-role.php', {
                method: 'POST',
                body: new URLSearchParams({ role_id: roleId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Only load the role details into the modal
                    document.getElementById('editRoleId').value = data.role.id;
                    document.getElementById('editRoleName').value = data.role.roles;
                    document.getElementById('editRoleDescription').value = data.role.description;
                    
                    // Show the role edit modal
                    document.getElementById('editRoleModal').style.display = 'flex';
                } else {
                    alert('Error fetching role data.');
                }
            });
        });
    });

    // Handle Delete Role Modal
    document.querySelectorAll('.deleteRole').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            const roleId = this.getAttribute('data-id');
            const roleName = this.getAttribute('data-name');

            document.getElementById('deleteRoleId').value = roleId;
            document.getElementById('deleteRoleName').textContent = roleName;
            document.getElementById('deleteRoleModal').style.display = 'flex';
        });
    });

    // Close Modal
    document.querySelectorAll('.close-modal').forEach(function(button) {
        button.addEventListener('click', function() {
            this.closest('.modal-background').style.display = 'none';
        });
    });

    // Handle Edit Role Form submission
const editRoleForm = document.getElementById('editRoleForm');
if (editRoleForm) {
    editRoleForm.addEventListener('submit', function(e) {
        e.preventDefault();

        fetch(editRoleForm.action, {
            method: 'POST',
            body: new FormData(editRoleForm)
        })
        .then(response => response.json())
        .then(data => {
            console.log('Edit Role Response:', data);  // Debugging log
            if (data.status === 'success') {
                showPopup('Role edited successfully!', 'success');
                setTimeout(function() {
                    // Force reload by redirecting to the same page
                    window.location.reload();
                }, 2200);  // Slight delay after the popup disappears
            } else {
                showPopup('Failed to edit Role. Please try again.', 'failure');
            }
        })
        .catch(error => {
            console.error('Error:', error);  // Debugging log
            showPopup('Error: ' + error, 'failure');
        });
    });
}

// Handle Delete Role Form submission
const deleteRoleForm = document.getElementById('deleteRoleForm');
if (deleteRoleForm) {
    deleteRoleForm.addEventListener('submit', function(e) {
        e.preventDefault();

        fetch(deleteRoleForm.action, {
            method: 'POST',
            body: new FormData(deleteRoleForm)
        })
        .then(response => response.json())
        .then(data => {
            console.log('Delete Role Response:', data);  // Debugging log
            if (data.status === 'success') {
                showPopup('Role deleted successfully!', 'success');
                setTimeout(function() {
                    // Force reload by redirecting to the same page
                    window.location.reload();
                }, 2200);  // Slight delay after the popup disappears
            } else {
                showPopup('Failed to delete Role. Please try again.', 'failure');
            }
        })
        .catch(error => {
            console.error('Error:', error);  // Debugging log
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
    }
});

</script>
</body>
</html>
