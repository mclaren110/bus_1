<?php
session_start();
include 'db.php'; // Ensure you have the database connection file

// Check user session
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Initialize variables for search and brand filtering
$searchTerm = trim($_GET['search'] ?? '');
$selectedBrand = $_GET['brand'] ?? '';

// Prepare the SQL query with filters
$query = "SELECT * FROM bus_information";
$conditions = [];

if ($selectedBrand) {
    $conditions[] = "brand = '" . $conn->real_escape_string($selectedBrand) . "'";
}

if ($searchTerm) {
    $conditions[] = "(plate_number LIKE '%" . $conn->real_escape_string($searchTerm) . "%' OR part_number LIKE '%" . $conn->real_escape_string($searchTerm) . "%')";
}

if ($conditions) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}

// Fetch data
$result = $conn->query($query);

// Set headers for download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="bus_information.csv"');

// Create a file pointer
$output = fopen('php://output', 'w');

// Output column headings matching your table structure
fputcsv($output, ['Date & Time', 'Brand', 'Plate Number', 'Quantity', 'Part Number', 'Description', 'Spare Part Info']);

// Output data rows
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Select the specific columns to output in the CSV
        fputcsv($output, [
            $row['created_at'], // Date & Time
            $row['brand'],      // Brand
            $row['plate_number'], // Plate Number
            $row['quantity'],    // Quantity
            $row['part_number'], // Part Number
            $row['description'], // Description
            $row['spare_part_info'] // Spare Part Info
        ]);
    }
}

fclose($output);
exit();
