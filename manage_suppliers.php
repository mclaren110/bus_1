<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Initialize messages
$success_message = "";
$error_message = "";

// Add supplier functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_supplier'])) {
    $supplier_name = trim($_POST['supplier_name']);
    $contact_info = trim($_POST['contact_info']);
    $reliability_rating = (int)$_POST['reliability_rating'];

    // Validate inputs
    if (empty($supplier_name) || empty($contact_info) || $reliability_rating < 1 || $reliability_rating > 5) {
        $error_message = "Please fill all fields correctly.";
    } else {
        $sql = "INSERT INTO suppliers (name, contact_info, reliability_rating) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssi", $supplier_name, $contact_info, $reliability_rating);
            if ($stmt->execute()) {
                $success_message = "Supplier added successfully!";
            } else {
                $error_message = "Error adding supplier: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error_message = "Error preparing statement: " . $conn->error;
        }
    }
}

// Handle deletion
if (isset($_GET['delete_id'])) {
    $supplier_id = (int)$_GET['delete_id'];
    $delete_sql = "DELETE FROM suppliers WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);

    if ($delete_stmt) {
        $delete_stmt->bind_param("i", $supplier_id);
        if ($delete_stmt->execute()) {
            $success_message = "Supplier deleted successfully!";
        } else {
            $error_message = "Error deleting supplier: " . $delete_stmt->error;
        }
        $delete_stmt->close();
    } else {
        $error_message = "Error preparing delete statement: " . $conn->error;
    }
}

// Handle editing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_supplier'])) {
    $supplier_id = (int)$_POST['supplier_id'];
    $supplier_name = trim($_POST['supplier_name']);
    $contact_info = trim($_POST['contact_info']);
    $reliability_rating = (int)$_POST['reliability_rating'];

    // Validate inputs
    if (empty($supplier_name) || empty($contact_info) || $reliability_rating < 1 || $reliability_rating > 5) {
        $error_message = "Please fill all fields correctly.";
    } else {
        $sql = "UPDATE suppliers SET name = ?, contact_info = ?, reliability_rating = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssii", $supplier_name, $contact_info, $reliability_rating, $supplier_id);
            if ($stmt->execute()) {
                $success_message = "Supplier updated successfully!";
            } else {
                $error_message = "Error updating supplier: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error_message = "Error preparing statement: " . $conn->error;
        }
    }
}

// Fetch suppliers
$result = $conn->query("SELECT * FROM suppliers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Suppliers</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            min-height: 100vh;
        }
        .sidebar {
            background-color: #343a40;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 200px;
            padding-top: 20px;
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
            margin-left: 200px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #343a40;
        }
        .alert {
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#"></a>
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
                    <a class="nav-link" href="view_parts.php">&nbsp;View Parts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="manage_suppliers.php">&nbsp;Manage Suppliers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="bus_information.php"><i class=""></i> Bus Information</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <main role="main" class="main-content">
        <div class="mt-3">
            <h1 class="mt-5">Manage Suppliers</h1>
            
            <form action="" method="post" class="mt-4">
                <div class="form-group">
                    <input type="text" name="supplier_name" class="form-control" placeholder="Supplier Name" required>
                </div>
                <div class="form-group">
                    <input type="text" name="contact_info" class="form-control" placeholder="Contact Information" required>
                </div>
                <div class="form-group">
                    <input type="number" name="reliability_rating" class="form-control" placeholder="Reliability Rating (1-5)" required min="1" max="5">
                </div>
                <button type="submit" name="add_supplier" class="btn btn-success">Add Supplier</button>
            </form>

            <?php if ($success_message): ?>
                <div class="alert alert-success mt-4"><?php echo $success_message; ?></div>
            <?php elseif ($error_message): ?>
                <div class="alert alert-danger mt-4"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <h2 class="mt-5">Current Suppliers</h2>
            
            <?php if ($result->num_rows > 0): ?>
                <table class="table table-striped mt-4">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Supplier Name</th>
                            <th>Contact Information</th>
                            <th>Reliability Rating</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['contact_info']); ?></td>
                                <td><?php echo $row['reliability_rating']; ?></td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal" 
                                            data-id="<?php echo $row['id']; ?>" 
                                            data-name="<?php echo htmlspecialchars($row['name']); ?>" 
                                            data-contact="<?php echo htmlspecialchars($row['contact_info']); ?>" 
                                            data-rating="<?php echo $row['reliability_rating']; ?>">
                                        Edit
                                    </button>
                                    <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info">No suppliers found.</div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editForm" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="supplier_id" id="supplier_id">
                        <div class="form-group">
                            <label for="edit_supplier_name">Supplier Name</label>
                            <input type="text" name="supplier_name" id="edit_supplier_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_contact_info">Contact Information</label>
                            <input type="text" name="contact_info" id="edit_contact_info" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_reliability_rating">Reliability Rating (1-5)</label>
                            <input type="number" name="reliability_rating" id="edit_reliability_rating" class="form-control" required min="1" max="5">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="edit_supplier" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var supplierId = button.data('id'); // Extract info from data-* attributes
            var supplierName = button.data('name');
            var contactInfo = button.data('contact');
            var reliabilityRating = button.data('rating');

            // Update the modal's content.
            var modal = $(this);
            modal.find('#supplier_id').val(supplierId);
            modal.find('#edit_supplier_name').val(supplierName);
            modal.find('#edit_contact_info').val(contactInfo);
            modal.find('#edit_reliability_rating').val(reliabilityRating);
        });
    </script>
</body>
</html>
