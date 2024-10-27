<?php
session_start();
include 'db.php'; // Ensure you have the database connection file

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Check user role
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Initialize variables for bus information
$busInfo = [];

// Fetch bus information from the database
$result = $conn->query("SELECT * FROM bus_information ORDER BY created_at DESC");

// Check if there are results
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $busInfo[] = $row;
    }
}

// Initialize variables for search and brand filtering
$searchTerm = '';
$selectedBrand = '';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $searchTerm = trim($_GET['search'] ?? '');
    $selectedBrand = $_GET['brand'] ?? '';
}

// Filter by brand if a brand is selected
if ($selectedBrand) {
    $busInfo = array_filter($busInfo, function($info) use ($selectedBrand) {
        return $info['brand'] === $selectedBrand; // Filter by the selected brand
    });
}

// Existing search logic (for search term)
if ($searchTerm) {
    $busInfo = array_filter($busInfo, function($info) use ($searchTerm) {
        return stripos($info['plate_number'], $searchTerm) !== false || // Filter by plate number
               stripos($info['part_number'], $searchTerm) !== false; // Filter by part number
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Precious Grace Bus Inventory System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            padding-top: 20px;
            position: fixed; /* Fixes the sidebar */
            width: 250px; /* Fixed width for the sidebar */
        }
        .sidebar a {
            color: #ffffff;
            padding: 10px 15px; /* Added padding for better spacing */
            border-radius: 4px; /* Rounded corners for links */
            display: block; /* Full-width links */
            margin: 5px 0; /* Margin between links */
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .main-content {
            margin-left: 270px; /* Space for the fixed sidebar */
            padding: 20px;
            background-image: url('images/p2p.jpg'); /* Add your background image here */
            background-size: 12%; /* Increased size for better visibility */
            background-position: top right 13px; /* Position the image at the top center */
            background-repeat: no-repeat; /* Prevent image from repeating */
            color: black; /* Set text color to black for better visibility */
            border-radius: 10px; /* Optional: Rounded corners for the main content */
            min-height: calc(100vh - 100px); /* Adjust minimum height if needed */
        }
        h1, h2, p {
            color: black; /* Set heading and paragraph text color to black */
        }
        .card {
            margin-top: 20px; /* Spacing between cards */
            border: none; /* Remove default border */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }
        .search-bar {
            display: flex;
            justify-content: space-between; /* Aligns items with space in between */
            margin-bottom: 20px; /* Margin below the search bar */
        }
        table {
            color: black; /* Set table text color to black */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Precious Grace Transport Services System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="sidebar">
        <h2 class="text-white text-center">Bus Inventory</h2>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="add_part.php"><i class="fas fa-plus"></i> Add Bus Part</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="view_parts.php"><i class="fas fa-bus"></i> View Parts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manage_suppliers.php"><i class="fas fa-truck"></i> Manage Suppliers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="bus_information.php"><i class="fas fa-info-circle"></i> Bus Information</a>
            </li>
            <?php if ($isAdmin): ?>
                <li class="nav-item">
                    <a class="nav-link" href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="main-content">
        <h1 class="text-center">Welcome to the Admin Dashboard</h1>

        <!-- Display the logged-in user's first name and role -->
        <p class="text-center">
            Hello, <?php echo htmlspecialchars($_SESSION['first_name'] ?? ''); ?>! 
            You are logged in as <strong><?php echo htmlspecialchars($_SESSION['role']); ?></strong>.
        </p>

        <!-- Logged Bus Information Table -->
        <h2 class="text-center mt-4">Logged Bus Information</h2>

        <!-- Search and Brand Filter -->
        <div class="search-bar">
            <form method="GET" class="form-inline">
                <div class="col-auto">
                    <select name="brand" class="form-control form-control-lg">
                        <option value="">All Brands</option>
                        <option value="Volvo" <?php echo (isset($_GET['brand']) && $_GET['brand'] == 'Volvo') ? 'selected' : ''; ?>>Volvo</option>
                        <option value="Golden Dragon" <?php echo (isset($_GET['brand']) && $_GET['brand'] == 'Golden Dragon') ? 'selected' : ''; ?>>Golden Dragon</option>
                        <option value="Nissan PKB" <?php echo (isset($_GET['brand']) && $_GET['brand'] == 'Nissan PKB') ? 'selected' : ''; ?>>Nissan PKB</option>
                        <option value="Nissan SP" <?php echo (isset($_GET['brand']) && $_GET['brand'] == 'Nissan SP') ? 'selected' : ''; ?>>Nissan SP</option>
                        <option value="Zhongtong" <?php echo (isset($_GET['brand']) && $_GET['brand'] == 'Zhongtong') ? 'selected' : ''; ?>>Zhongtong</option>
                        <option value="Daewoo" <?php echo (isset($_GET['brand']) && $_GET['brand'] == 'Daewoo') ? 'selected' : ''; ?>>Daewoo</option>
                        <option value="Iveco" <?php echo (isset($_GET['brand']) && $_GET['brand'] == 'Iveco') ? 'selected' : ''; ?>>Iveco</option>
                    </select>
                </div>
                <input type="text" name="search" class="form-control mr-2" placeholder="Search by plate number or part number" value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="download_excel.php?search=<?php echo urlencode($searchTerm); ?>&brand=<?php echo urlencode($selectedBrand); ?>" class="btn btn-success ml-2">Download Excel</a>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Brand</th>
                        <th>Plate Number</th>
                        <th>Quantity</th>
                        <th>Part Number</th>
                        <th>Description</th>
                        <th>Spare Part Info</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($busInfo)): ?>
                        <tr>
                            <td colspan="7" class="text-center">No bus information found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($busInfo as $info): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($info['created_at']); ?></td>
                                <td><?php echo htmlspecialchars($info['brand']); ?></td>
                                <td><?php echo htmlspecialchars($info['plate_number']); ?></td>
                                <td><?php echo htmlspecialchars($info['quantity']); ?></td>
                                <td><?php echo htmlspecialchars($info['part_number']); ?></td>
                                <td><?php echo htmlspecialchars($info['description']); ?></td>
                                <td><?php echo htmlspecialchars($info['spare_part_info']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
