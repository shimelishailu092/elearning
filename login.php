<?php
session_start();
include 'db.php';

$error = '';
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $res = mysqli_query($conn, "SELECT * FROM admins WHERE username='$username' AND password='$password' AND role='admin'");
    if (mysqli_num_rows($res) == 1) {
        $_SESSION['admin_username'] = $username;
        //$_SESSION['role'] = $role;
        header("Location: home.php");
        exit();
    }
    $res1 = mysqli_query($conn, "SELECT * FROM admins WHERE username='$username' AND password='$password' AND role='student'");
    if (mysqli_num_rows($res1) == 1) {
        $_SESSION['admin_username'] = $username;
        //$_SESSION['role'] = $role;
        header("Location: ../student/uploads/login.php");
        exit();
    }

    else {
        $error = "Invalid login credentials!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f0f0f0;
            height: 100vh;
            margin: 0;
        }

        .top-left-msg {
            position: absolute;
            top: 15px;
            left: 20px;
            font-weight: bold;
            color: green;
        }

        .login-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        label {
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px 35px 10px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-group i {
            position: absolute;
            top: 35px;
            right: 10px;
            color: #aaa;
        }

        .btn-login {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
        }

        .btn-login:hover {
            background-color: #0056b3;
        }

        .extra-links,
        .copyright {
            text-align: center;
            margin-top: 15px;
        }

        .extra-links a {
            color: #007bff;
            text-decoration: none;
        }

        .extra-links a:hover {
            text-decoration: underline;
        }

        .error-msg {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>


<div class="login-wrapper">
    <div class="login-container">
        <h2>Admin Login</h2>

        <?php if ($error): ?>
            <div class="error-msg"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="Enter your username" required>
                <i class="fas fa-user"></i>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
                <i class="fas fa-lock"></i>
            </div>
            <button type="submit" name="login" class="btn btn-login">Login</button>
        </form>

        <div class="extra-links">
            <a href="forgot_password.php">Forgot password</a><br>
            <a href="register.php">OR Click here to create account</a>
        </div>

        <div class="copyright">
            &copy; 2014-2015 All rights reserved
        </div>
    </div>
</div>

</body>
</html>
