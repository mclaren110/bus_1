<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Precious Grace Bus Inventory System - Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-image: url('images/p2p.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: white;
        }
        .card {
            width: 500px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 40px rgba(0, 0, 0, 0.7);
            background-color: rgba(255, 255, 255, 0.9);
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            transition: background-color 0.3s;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        h3 {
            margin-bottom: 20px;
            font-weight: 500;
            color: #333;
            text-align: center;
        }
        label {
            color: black;
        }
        .text-center a {
            color: #007bff;
            text-decoration: none;
        }
        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="card">
        <h3 class="text-center">Precious Grace Bus Inventory System</h3>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="authenticate.php" method="post" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="username">Email</label>
                <input type="text" name="username" id="username" class="form-control" required 
                       oninvalid="this.setCustomValidity('Please enter your  email.')" 
                       oninput="this.setCustomValidity('')">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required 
                       oninvalid="this.setCustomValidity('Please enter your password.')" 
                       oninput="this.setCustomValidity('')">
            </div>
            <button type="submit" class="btn btn-custom btn-block">Login</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.2.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
