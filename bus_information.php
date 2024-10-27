<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check the current user's role
$current_user_role = $_SESSION['role'] ?? 'user'; // Default to 'user' if not set

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Information - Precious Grace Bus Inventory System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            display: flex;
        }
        .sidebar {
            background-color: #343a40; /* Dark sidebar */
            height: 100vh; /* Full height */
            position: fixed; /* Fix sidebar position */
            top: 0; /* Align to top */
            left: 0; /* Align to left */
            width: 250px; /* Adjusted width for sidebar */
            padding-top: 20px; /* Padding for the sidebar */
        }
        .sidebar a {
            color: #ffffff;
            padding: 10px 15px;
            display: block;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .main-content {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 250px);
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            margin-bottom: 20px;
            color: #343a40;
        }
        .card {
            margin-top: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
    </style>
</head>
<body>
<nav class="sidebar">
        <div class="sidebar-sticky">
            <h4 class="sidebar-heading text-white">&nbsp;&nbsp;Bus Inventory</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="add_part.php">&nbsp;Add Bus Part</a>
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
                <?php if ($current_user_role === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_users.php">&nbsp;Manage Users</a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="main-content">
        <h1 class="text-center">Bus Information</h1>

        <!-- Success Notification -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success_message']; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['success_message']); // Clear the message after displaying ?>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form action="save_bus_info.php" method="POST">
                    <div class="form-group">
                        <label for="busBrand">Brand of Bus</label>
                        <select class="form-control" id="busBrand" name="busBrand" required>
                            <option value="">Select Brand</option>
                            <option value="Volvo">Volvo</option>
                            <option value="Golden Dragon">Golden Dragon</option>
                            <option value="Nissan PKB">Nissan PKB</option>
                            <option value="Nissan SP">Nissan SP</option>
                            <option value="Zhongtong">Zhongtong</option>
                            <option value="Daewoo">Daewoo</option>
                            <option value="Iveco">Iveco</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="plateNumber">Plate Number</label>
                        <input type="text" class="form-control" id="plateNumber" name="plateNumber" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required min="1">
                    </div>
                    <div class="form-group">
                        <label for="partNumber">Part Number</label>
                        <input type="text" class="form-control" id="partNumber" name="partNumber" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="sparePartInfo">Spare Part Info</label>
                        <textarea class="form-control" id="sparePartInfo" name="sparePartInfo" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
