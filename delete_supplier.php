<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $supplier_id = (int)$_POST['id'];
    
    $delete_sql = "DELETE FROM suppliers WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);

    if ($delete_stmt) {
        $delete_stmt->bind_param("i", $supplier_id);
        if ($delete_stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $delete_stmt->error]);
        }
        $delete_stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
