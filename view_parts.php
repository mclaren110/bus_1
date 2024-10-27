<?php
session_start();
// Other includes and checks...

// Check if there's an alert message to display
$alert_message = isset($_SESSION['discount_approved']) ? $_SESSION['discount_approved'] : null;

// Clear the alert message after displaying it
unset($_SESSION['discount_approved']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Your head content here -->
</head>
<body>
    <main role="main" class="main-content">
        <div class="mt-3">
            <?php if ($alert_message): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($alert_message); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <h1 class="mt-5">Bus Parts List</h1>
            <!-- Rest of your content here -->
        </div>
    </main>
