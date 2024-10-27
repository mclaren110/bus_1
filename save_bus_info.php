<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $busBrand = $_POST['busBrand'];
    $plateNumber = $_POST['plateNumber'];
    $quantity = intval($_POST['quantity']);
    $partNumber = $_POST['partNumber'];
    $description = $_POST['description'];
    $sparePartInfo = $_POST['sparePartInfo'];

    // Insert bus information into the bus_information table
    $stmt = $conn->prepare("INSERT INTO bus_information (brand, plate_number, quantity, part_number, description, spare_part_info) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisss", $busBrand, $plateNumber, $quantity, $partNumber, $description, $sparePartInfo);

    if ($stmt->execute()) {
        // Check current quantity in bus_parts using either part_number or description
        $checkStmt = $conn->prepare("SELECT quantity FROM bus_parts WHERE part_number = ? OR description = ?");
        $checkStmt->bind_param("ss", $partNumber, $description);
        $checkStmt->execute();
        $checkStmt->bind_result($currentQuantity);
        $checkStmt->fetch();
        $checkStmt->close();

        // Only update the quantity if enough stock is available
        if ($currentQuantity >= $quantity) {
            $updateStmt = $conn->prepare("UPDATE bus_parts SET quantity = quantity - ? WHERE part_number = ? OR description = ?");
            $updateStmt->bind_param("iss", $quantity, $partNumber, $description);
            
            if ($updateStmt->execute()) {
                $_SESSION['success_message'] = "Bus information saved and quantity updated successfully.";
            } else {
                $_SESSION['success_message'] = "Bus information saved, but failed to update quantity.";
            }
            
            $updateStmt->close();
        } else {
            $_SESSION['success_message'] = "Not enough stock available for the requested quantity.";
        }
    } else {
        $_SESSION['success_message'] = "Failed to save bus information.";
    }

    $stmt->close();
    $conn->close();

    header("Location: bus_information.php");
    exit();
}
?>
