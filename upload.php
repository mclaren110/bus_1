<?php
require 'vendor/autoload.php'; // Include PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\IOFactory;

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];
    
    // Load the spreadsheet
    $spreadsheet = IOFactory::load($file);
    $worksheet = $spreadsheet->getActiveSheet();

    // Loop through each row of the spreadsheet
    foreach ($worksheet->getRowIterator() as $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false); // This will include empty cells
        $data = [];

        foreach ($cellIterator as $cell) {
            $data[] = $cell->getValue();
        }

        // Assuming the order of columns matches your database
        if (count($data) >= 6) { // Adjust if needed
            $id = $data[0];
            $part_number = $data[1];
            $description = $data[2];
            $quantity = $data[3];
            $min_reorder_qty = $data[4];
            $supplier_info = $data[5];
            $price = $data[6];

            // Update the record in the database
            $sql = "UPDATE bus_parts SET part_number='$part_number', description='$description', quantity=$quantity, min_reorder_qty=$min_reorder_qty, supplier_info='$supplier_info', price='$price', last_reordered_date=NOW() WHERE id=$id";
            $conn->query($sql);
        }
    }

    echo "<div class='alert alert-success'>Data imported successfully!</div>";
    echo "<a href='view_parts.php' class='btn btn-link'>Back to Parts List</a>";
} else {
    echo "<div class='alert alert-danger'>No file uploaded.</div>";
}
?>
