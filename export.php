<?php
include_once 'db.php'; // Ensure database connection

// Get search input
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Prepare SQL query with filtering
$query = "SELECT * FROM bus_parts WHERE part_number LIKE '%$search%' OR description LIKE '%$search%'";
$result = $conn->query($query);

// Prepare to download as Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="bus_parts.xlsx"');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Expires: 0');

echo "ID\tPart Number\tDescription\tQuantity\tMin Reorder Qty\tLast Reordered Date\tSupplier Info\tPrice\n";

while ($row = $result->fetch_assoc()) {
    echo "{$row['id']}\t{$row['part_number']}\t{$row['description']}\t{$row['quantity']}\t{$row['min_reorder_qty']}\t{$row['last_reordered_date']}\t{$row['supplier_info']}\t{$row['price']}\n";
}
exit;
?>
