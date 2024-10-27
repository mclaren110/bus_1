<?php
session_start();
include 'db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Get the part details from pending table
    $stmt = $conn->prepare("SELECT * FROM pending_bus_parts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $part = $result->fetch_assoc();
        // Insert into bus_parts
        $stmt_insert = $conn->prepare("INSERT INTO bus_parts (part_number, description, quantity, min_reorder_qty, supplier_info, price, last_reordered_date) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt_insert->bind_param("ssiiss", $part['part_number'], $part['description'], $part['quantity'], $part['min_reorder_qty'], $part['supplier_info'], $part['price']);
        $stmt_insert->execute();
        
        // Delete from pending table
        $stmt_delete = $conn->prepare("DELETE FROM pending_bus_parts WHERE id = ?");
        $stmt_delete->bind_param("i", $id);
        $stmt_delete->execute();

        header("Location: view_parts.php?msg=Part approved successfully.");
    }
}
?>
