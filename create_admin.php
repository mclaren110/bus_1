<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'bus_inventory_db'); // Update with your DB credentials

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the admin account already exists
$result = $conn->query("SELECT * FROM users WHERE username = 'admin'");
if ($result->num_rows === 0) {
    // Define admin credentials
    $username = 'admin';
    $password = password_hash('admin_password', PASSWORD_DEFAULT); // Change 'admin_password' to your desired password
    $first_name = 'Admin';
    $last_name = 'User';
    $is_active = 1; // Active status
    $is_approved = 1; // Approved status
    $role = 'admin'; // User role

    // Insert the new admin account into the database
    $stmt = $conn->prepare("INSERT INTO users (username, password, first_name, last_name, is_active, is_approved, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssiis", $username, $password, $first_name, $last_name, $is_active, $is_approved, $role);

    if ($stmt->execute()) {
        echo "Admin account created successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Admin account already exists.";
}

$conn->close();
?>
