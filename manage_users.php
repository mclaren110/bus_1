<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check the current user's role
$current_user_role = $_SESSION['role'] ?? 'user'; // Default to 'user' if not set

// Handle user creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, is_approved, role, first_name, last_name) VALUES (?, ?, 0, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $password, $role, $first_name, $last_name);
    $stmt->execute();
}

// Handle user approval
if (isset($_GET['approve_id'])) {
    $approve_id = $_GET['approve_id'];
    $stmt = $conn->prepare("UPDATE users SET is_approved = 1 WHERE id = ?");
    $stmt->bind_param("i", $approve_id);
    $stmt->execute();
    header("Location: manage_users.php");
    exit();
}

// Handle user deactivation
if (isset($_GET['deactivate_id'])) {
    $deactivate_id = $_GET['deactivate_id'];
    $stmt = $conn->prepare("UPDATE users SET is_approved = 0 WHERE id = ?");
    $stmt->bind_param("i", $deactivate_id);
    $stmt->execute();
    header("Location: manage_users.php");
    exit();
}

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: manage_users.php");
    exit();
}

// Handle user editing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $edit_id = $_POST['edit_id'];
    $new_first_name = $_POST['new_first_name'];
    $new_last_name = $_POST['new_last_name'];
    $new_username = $_POST['new_username'];
    $new_password = $_POST['new_password'];
    $new_role = $_POST['new_role'];

    // Prepare the SQL statement
    if (!empty($new_password)) {
        // Update with new password
        $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, username = ?, password = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $new_first_name, $new_last_name, $new_username, $new_password_hashed, $new_role, $edit_id);
    } else {
        // Update without changing the password
        $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, username = ?, role = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $new_first_name, $new_last_name, $new_username, $new_role, $edit_id);
    }
    $stmt->execute();
}

// Fetch users
$query = "SELECT * FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4; /* Light background */
            min-height: 100vh; /* Ensure body takes full height */
            display: flex; /* Use flex for the main layout */
        }
        .sidebar {
            background-color: #343a40; /* Dark sidebar */
            height: 100vh; /* Full height */
            position: fixed; /* Fix sidebar position */
            top: 0; /* Align to top */
            left: 0; /* Align to left */
            width: 200px; /* Adjusted width for sidebar */
            padding-top: 20px; /* Padding for the sidebar */
        }
        .sidebar a {
            color: #ffffff; /* White text */
            padding: 10px 15px; /* Reduced padding for links */
            display: block; /* Full width for clickable area */
        }
        .sidebar a:hover {
            background-color: #495057; /* Lighter hover */
        }
        .main-content {
            margin-left: 200px; /* Leave space for sidebar */
            padding: 30px; /* Increased padding */
            background-color: #ffffff; /* White content background */
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); /* Subtle shadow */
            flex: 1; /* Allow main content to grow */
        }
        h1 {
            color: #343a40; /* Dark text for headings */
        }
        .alert {
            border-radius: 5px; /* Rounded alert boxes */
        }

    </style>
</head>
<body>
    <nav class="sidebar">
        <div class="sidebar-sticky">
            <h4 class="sidebar-heading text-white">&nbsp;&nbsp;Bus Inventory</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="add_part.php">&nbsp;Add Bus Part</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view_parts.php">&nbsp;View Parts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_suppliers.php">&nbsp;Manage Suppliers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="bus_information.php"><i class=""></i> Bus Information</a>
                </li>
                <?php if ($current_user_role === 'admin' || $current_user_role === 'manager'): ?>
                    <li class="nav-item">
                        <a class="nav-link active" href="manage_users.php">&nbsp;Manage Users</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                <a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <main role="main" class="main-content">
        <div class="mt-3">
            <?php if ($current_user_role === 'admin' || $current_user_role === 'manager'): ?>
                <h1 class="mt-5">Manage Users</h1>
                <form method="POST" class="mb-4">
                    <h5>Create New User</h5>
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" name="first_name" id="first_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Email</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" name="create_user" class="btn btn-primary">Create User</button>
                </form>

                <h5>List of Users</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Username</th>
                            <th>Approved</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['first_name']; ?></td>
                                <td><?php echo $row['last_name']; ?></td>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $row['is_approved'] ? 'Yes' : 'No'; ?></td>
                                <td><?php echo $row['role']; ?></td>
                                <td>
                                    <?php if (!$row['is_approved']): ?>
                                        <a href="manage_users.php?approve_id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Approve</a>
                                    <?php else: ?>
                                        <a href="manage_users.php?deactivate_id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Deactivate</a>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editUserModal<?php echo $row['id']; ?>">Edit</button>
                                    <a href="manage_users.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>

                            <!-- Edit User Modal -->
                            <div class="modal fade" id="editUserModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                                                <div class="form-group">
                                                    <label for="new_first_name">First Name</label>
                                                    <input type="text" name="new_first_name" id="new_first_name" class="form-control" value="<?php echo $row['first_name']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="new_last_name">Last Name</label>
                                                    <input type="text" name="new_last_name" id="new_last_name" class="form-control" value="<?php echo $row['last_name']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="new_username">Email</label>
                                                    <input type="text" name="new_username" id="new_username" class="form-control" value="<?php echo $row['username']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="new_password">Password</label>
                                                    <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Enter new password if changing">
                                                </div>
                                                <div class="form-group">
                                                    <label for="new_role">Role</label>
                                                    <select name="new_role" id="new_role" class="form-control" required>
                                                        <option value="user" <?php if ($row['role'] == 'user') echo 'selected'; ?>>User</option>
                                                        <option value="admin" <?php if ($row['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="edit_user" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-danger" role="alert">You do not have permission to manage users.</div>
            <?php endif; ?>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
