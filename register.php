<?php
session_start();
include 'db.php';

$message = '';
if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $check = mysqli_query($conn, "SELECT * FROM admins WHERE username='$username'");
    if (mysqli_num_rows($check) > 0) {
        $message = "❌ Username already exists!";
    } else {
        mysqli_query($conn, "INSERT INTO admins (username, password) VALUES ('$username', '$password')");
        $message = "✅ Account created successfully. You can now <a href='login.php'>login</a>.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Admin Account</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="bg-white p-5 rounded shadow-sm" style="width: 350px;">
        <h4 class="text-center mb-4">Register Admin</h4>
        <?php if ($message): ?>
            <div class="alert alert-info text-center"><?= $message ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="register" class="btn btn-success w-100">Create Account</button>
            <div class="mt-3 text-center">
                <a href="login.php">Back to Login</a>
            </div>
        </form>
    </div>
</body>
</html>
