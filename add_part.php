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
    <title>Add Bus Part</title>
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

    <main role="main" class="main-content">
        <div class="mt-3">
            <h1>Add Bus Part</h1>
            <form action="" method="post" class="mt-4">
                <div class="form-group">
                    <label for="part_number">Part Number</label>
                    <input type="text" name="part_number" class="form-control form-control-lg" placeholder="Part Number" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" name="description" class="form-control form-control-lg" placeholder="Description" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" name="quantity" class="form-control form-control-lg" placeholder="Quantity" required>
                </div>
                <div class="form-group">
                    <label for="min_reorder_qty">Minimum Reorder Quantity</label>
                    <input type="number" name="min_reorder_qty" class="form-control form-control-lg" placeholder="Minimum Reorder Quantity" required>
                </div>
                <div class="form-group">
                    <label for="supplier_info">Supplier Info</label>
                    <input type="text" name="supplier_info" class="form-control form-control-lg" placeholder="Supplier Info" required>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="text" name="price" class="form-control form-control-lg" placeholder="Price" required>
                </div>
                <button type="submit" class="btn btn-primary btn-lg">Submit</button>
            </form>
            <a class="btn btn-link mt-3" href="dashboard.php">Back to Home</a>

            <?php
            date_default_timezone_set('Asia/Manila'); // Set the timezone to Philippine time

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $part_number = $_POST['part_number'];
                $description = $_POST['description'];
                $quantity = $_POST['quantity'];
                $min_reorder_qty = $_POST['min_reorder_qty'];
                $supplier_info = $_POST['supplier_info'];
                $price = $_POST['price'];

                // Check if the part already exists
                $stmt = $conn->prepare("SELECT id, quantity, min_reorder_qty, price FROM bus_parts WHERE part_number = ?");
                $stmt->bind_param("s", $part_number);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    // Part exists, get the existing details
                    $stmt->bind_result($id, $existing_quantity, $existing_min_reorder_qty, $existing_price);
                    $stmt->fetch();
                    
                    // Calculate the new quantity
                    $new_quantity = $existing_quantity + $quantity;

                    // Update only the quantity, min_reorder_qty, and price if they have changed
                    $stmt->close();
                    $stmt = $conn->prepare("UPDATE bus_parts SET quantity=?, min_reorder_qty=?, price=?, last_reordered_date=NOW() WHERE part_number=?");
                    $stmt->bind_param("iiis", $new_quantity, $min_reorder_qty, $price, $part_number);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success mt-3'>Part quantity, minimum reorder quantity, and price updated successfully</div>";
                    } else {
                        echo "<div class='alert alert-danger mt-3'>Error: " . $stmt->error . "</div>";
                    }
                } else {
                    // Part does not exist, insert it
                    $stmt->close();
                    $stmt = $conn->prepare("INSERT INTO bus_parts (part_number, description, quantity, min_reorder_qty, supplier_info, price, last_reordered_date) VALUES (?, ?, ?, ?, ?, ?, NOW())");
                    $stmt->bind_param("ssiiss", $part_number, $description, $quantity, $min_reorder_qty, $supplier_info, $price);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success mt-3'>New part added successfully</div>";
                    } else {
                        echo "<div class='alert alert-danger mt-3'>Error: " . $stmt->error . "</div>";
                    }
                }

                // Close the statement
                $stmt->close();
            }
            ?>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
