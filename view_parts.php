<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get the current user's role
$current_user_role = $_SESSION['role'] ?? 'user'; // Default to 'user' if not set
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bus Parts</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4; /* Light background */
            min-height: 100vh; /* Ensure body takes full height */
            display: flex; /* Use flex for the main layout */
            flex-direction: column; /* Allow column layout */
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
        .table th, .table td {
            vertical-align: middle; /* Center align table cells */
        }
        .navbar {
            margin-left: 200px; /* Offset for the sidebar */
        }
    </style>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
            </ul>
        </div>
    </nav>

    <nav class="sidebar">
        <div class="sidebar-sticky">
            <h4 class="sidebar-heading text-white">&nbsp;&nbsp;Bus Inventory</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="add_part.php">&nbsp;Add Bus Part</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="view_parts.php">&nbsp;View Parts</a>
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

    <main role="main" class="main-content">
        <div class="mt-3">
            <h1 class="mt-5">Bus Parts List</h1>

            <form method="GET" class="mb-4">
                <div class="form-row align-items-end">
                    <div class="col-auto">
                        <input type="text" name="search" class="form-control form-control-lg" placeholder="Search by Part Number or Description" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                    <div class="col-auto">
                        <select name="status" class="form-control form-control-lg">
                            <option value="">All Statuses</option>
                            <option value="in_stock" <?php echo (isset($_GET['status']) && $_GET['status'] == 'in_stock') ? 'selected' : ''; ?>>In Stock</option>
                            <option value="low_stock" <?php echo (isset($_GET['status']) && $_GET['status'] == 'low_stock') ? 'selected' : ''; ?>>Low Stock</option>
                            <option value="medium_stock" <?php echo (isset($_GET['status']) && $_GET['status'] == 'medium_stock') ? 'selected' : ''; ?>>Medium Stock</option>
                            <option value="out_of_stock" <?php echo (isset($_GET['status']) && $_GET['status'] == 'out_of_stock') ? 'selected' : ''; ?>>Out of Stock</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-secondary btn-lg">Search</button>
                        <a href="export.php?search=<?php echo isset($_GET['search']) ? urlencode($_GET['search']) : ''; ?>" class="btn btn-primary btn-lg ml-2">Download Excel</a>
                    </div>
                </div>
            </form>

            <?php
            // Ensure database connection
            include_once 'db.php';

            // Get search input and status filter
            $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
            $status_filter = isset($_GET['status']) ? $_GET['status'] : '';

            // Prepare SQL query with filtering
            $query = "SELECT * FROM bus_parts WHERE (part_number LIKE '%$search%' OR description LIKE '%$search%')";

            // Add status filtering logic
            if ($status_filter === 'in_stock') {
                $query .= " AND quantity > 50";
            } elseif ($status_filter === 'low_stock') {
                $query .= " AND quantity < 20";
            } elseif ($status_filter === 'medium_stock') {
                $query .= " AND quantity >= 20 AND quantity < 50";
            } elseif ($status_filter === 'out_of_stock') {
                $query .= " AND quantity <= 0";
            }

            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                echo '<table class="table table-striped mt-4">';
                echo '<thead>
                        <tr>
                            <th>ID</th>
                            <th>Part Number</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Min Reorder Qty</th>
                            <th>Last Reordered Date</th>
                            <th>Supplier Info</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>';

                while ($row = $result->fetch_assoc()) {
                    // Format the last reordered date (without time)
                    $last_reordered_date = date('F j, Y', strtotime($row['last_reordered_date']));

                    // Determine status based on quantity
                    $status = '';
                    if ($row['quantity'] <= 0) {
                        $status = '<span class="text-danger">Out of Stock</span>';
                    } elseif ($row['quantity'] < 20) {
                        $status = '<span class="text-warning">Low Stock</span>';
                    } elseif ($row['quantity'] < 50) {
                        $status = '<span class="text-info">Medium Stock</span>';
                    } else {
                        $status = '<span class="text-success">High Stock</span>';
                    }

                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['part_number']}</td>
                            <td>{$row['description']}</td>
                            <td>{$row['quantity']}</td>
                            <td>{$row['min_reorder_qty']}</td>
                            <td>{$last_reordered_date}</td>
                            <td>{$row['supplier_info']}</td>
                            <td>{$row['price']}</td>
                            <td>{$status}</td>
                            <td>
                                <a href='edit_part.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='delete_part.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirmDelete();'>Delete</a>
                            </td>
                          </tr>";
                }

                echo '</tbody></table>';
            } else {
                echo '<div class="alert alert-warning" role="alert">No bus parts found.</div>';
            }

            // Close the database connection
            $conn->close();
            ?>

        </div>
    </main>

    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this part?");
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
