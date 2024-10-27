<?php
include 'db.php';
$id = $_GET['id'];
$sql = "DELETE FROM bus_parts WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    header("Location: view_parts.php?message=Part deleted successfully");
} else {
    echo "Error deleting record: " . $conn->error;
}
?>
