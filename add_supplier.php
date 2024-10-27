<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Supplier</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Add Supplier</h1>
        <form action="add_supplier.php" method="post" class="mt-4">
            <div class="form-group">
                <input type="text" name="supplier_name" class="form-control" placeholder="Supplier Name" required>
            </div>
            <div class="form-group">
                <input type="text" name="contact_info" class="form-control" placeholder="Contact Information" required>
            </div>
            <div class="form-group">
                <input type="number" name="reliability_rating" class="form-control" placeholder="Reliability Rating (1-5)" required>
            </div>
            <button type="submit" class="btn btn-success">Add Supplier</button>
        </form>
        <a class="btn btn-link mt-3" href="dashboard.php">Back to Home</a>

        <?php
        include 'db.php';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $supplierData = [
                $_POST['supplier_name'],
                $_POST['contact_info'],
                $_POST['reliability_rating']
            ];

            addSupplier($supplierData);
            echo "<div class='alert alert-success mt-3'>New supplier added successfully</div>";
        }
        ?>
    </div>
</body>
</html>
