<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bus Part</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Edit Bus Part</h1>
        <?php
        include 'db.php';
        $id = $_GET['id'];
        $result = $conn->query("SELECT * FROM bus_parts WHERE id=$id");
        $part = $result->fetch_assoc();

        // Check if the form was submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get updated values from form
            $part_number = $_POST['part_number'];
            $description = $_POST['description'];
            $quantity = $_POST['quantity'];
            $min_reorder_qty = $_POST['min_reorder_qty'];
            $supplier_info = $_POST['supplier_info'];
            $price = $_POST['price'];

            // Update query
            $sql = "UPDATE bus_parts SET part_number='$part_number', description='$description', quantity=$quantity, min_reorder_qty=$min_reorder_qty, supplier_info='$supplier_info', price='$price' WHERE id=$id";

            // Execute update and check for success
            if ($conn->query($sql) === TRUE) {
                echo "<div class='alert alert-success mt-3'>Part updated successfully</div>";

                // Fetch updated part information
                $result = $conn->query("SELECT * FROM bus_parts WHERE id=$id");
                $part = $result->fetch_assoc();
            } else {
                echo "<div class='alert alert-danger mt-3'>Error: " . $sql . "<br>" . $conn->error . "</div>";
            }
        }
        ?>

        <form action="edit_part.php?id=<?php echo $id; ?>" method="post" class="mt-4">
            <div class="form-group">
                <label for="part_number">Part Number</label>
                <input type="text" name="part_number" id="part_number" class="form-control" value="<?php echo isset($part['part_number']) ? htmlspecialchars($part['part_number']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" name="description" id="description" class="form-control" value="<?php echo isset($part['description']) ? htmlspecialchars($part['description']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="form-control" value="<?php echo isset($part['quantity']) ? htmlspecialchars($part['quantity']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="min_reorder_qty">Min Reorder Qty</label>
                <input type="number" name="min_reorder_qty" id="min_reorder_qty" class="form-control" value="<?php echo isset($part['min_reorder_qty']) ? htmlspecialchars($part['min_reorder_qty']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="supplier_info">Supplier Info</label>
                <input type="text" name="supplier_info" id="supplier_info" class="form-control" value="<?php echo isset($part['supplier_info']) ? htmlspecialchars($part['supplier_info']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" name="price" id="price" class="form-control" value="<?php echo isset($part['price']) ? htmlspecialchars($part['price']) : ''; ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Update Part</button>
        </form>
        <a class="btn btn-link mt-3" href="view_parts.php">Back to Parts List</a>
    </div>
</body>
</html>
