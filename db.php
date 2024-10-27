<?php
// Database connection settings
$host = 'localhost'; // Adjust as needed
$db = 'bus_inventory'; // Adjust to your database name
$user = 'root'; // Adjust as needed
$pass = ''; // Adjust as needed

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to add supplier details
function addSupplier($supplierData) {
    global $conn; // Use the database connection from the included file

    $sql = "INSERT INTO suppliers (name, contact_info, reliability_rating) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        // Bind parameters (name, contact_info, reliability_rating)
        $stmt->bind_param("ssi", $supplierData[0], $supplierData[1], $supplierData[2]);

        // Execute the statement
        if ($stmt->execute()) {
            return true; // Return true on success
        } else {
            echo 'Error adding supplier: ' . $stmt->error; // Display error if execution fails
        }

        $stmt->close(); // Close the statement
    } else {
        echo 'Error preparing statement: ' . $conn->error; // Display error if preparation fails
    }
    
    return false; // Return false on failure
}
?>
