<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $is_approved = isset($_POST['is_approved']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE users SET username=?, is_approved=? WHERE id=?");
    $stmt->bind_param("sii", $username, $is_approved, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php"); // Redirect after update
    exit();
}

$user_id = $_GET['id'];
$user_result = $conn->query("SELECT * FROM users WHERE id=$user_id");
$user = $user_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Edit User</h1>
        <form action="edit_user.php" method="post">
            <input type="hidden" name="id" value="<?= $user['id'] ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" value="<?= $user['username'] ?>" required>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_approved" <?= $user['is_approved'] ? 'checked' : '' ?>> Approved
                </label>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
            <a class="btn btn-secondary" href="dashboard.php">Cancel</a>
        </form>
    </div>
</body>
</html>
