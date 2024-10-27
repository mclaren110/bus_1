<!-- <?php
session_start();
include 'db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Delete from pending table
    $stmt = $conn->prepare("DELETE FROM pending_bus_parts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: view_parts.php?msg=Part declined successfully.");
}
?> -->
