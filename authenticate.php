<?php
session_start();
include 'db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("SELECT password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if the user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password, $role);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Check if the user is approved
            if ($role === 'admin' || $role === 'user') { // Check roles accordingly
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;

                // Redirect based on user role
                if ($role === 'admin') {
                    header("Location: dashboard.php"); // Redirect to admin page
                } else {
                    header("Location: dashboard.php"); // Redirect to user dashboard
                }
                exit();
            } else {
                echo "<script>alert('User not approved.'); window.location.href='login.php';</script>";
            }
        } else {
            echo "<script>alert('Invalid password. Please try again.'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Username not found. Please try again.'); window.location.href='login.php';</script>";
    }

    $stmt->close(); // Close the statement
}
?>
