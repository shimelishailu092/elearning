<?php
session_start();
include 'db.php';

$connection_status = $conn ? "" : "";
$message = '';

if (isset($_POST['reset'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);

    $checkUser = mysqli_query($conn, "SELECT * FROM admins WHERE username='$username'");
    if (mysqli_num_rows($checkUser) == 1) {
        mysqli_query($conn, "UPDATE admins SET password='$new_password' WHERE username='$username'");
        $message = "✅ Password has been updated successfully.";
    } else {
        $message = "❌ Username not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .connection-status {
            position: absolute;
            top: 15px;
            left: 20px;
            font-size: 14px;
            color: green;
            font-weight: 500;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

    <?php if ($connection_status): ?>
        <div class="connection-status"><?= $connection_status ?></div>
    <?php endif; ?>

    <div class="bg-white p-5 rounded shadow-sm" style="width: 350px;">
        <h4 class="text-center mb-4">Reset Password</h4>
        <?php if ($message): ?>
            <div class="alert alert-info text-center"><?= $message ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <button type="submit" name="reset" class="btn btn-primary w-100">Reset Password</button>
            <div class="mt-3 text-center">
                <a href="login.php">← Back to Login</a>
            </div>
        </form>
    </div>
</body>
</html>
