<?php
include 'db.php';
$message = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $check = mysqli_query($conn, "SELECT * FROM admins WHERE reset_token='$token' AND token_expiry > NOW()");

    if (mysqli_num_rows($check) == 1) {
        if (isset($_POST['reset'])) {
            $new_pass = mysqli_real_escape_string($conn, $_POST['password']);
            mysqli_query($conn, "UPDATE admins SET password='$new_pass', reset_token=NULL, token_expiry=NULL WHERE reset_token='$token'");
            $message = "✅ Password has been reset. You can now <a href='login.php'>login</a>.";
        }
    } else {
        $message = "❌ Invalid or expired token.";
    }
} else {
    $message = "❌ No token provided.";
}
?>

<!-- HTML Form -->
<form method="POST">
    <input type="password" name="password" placeholder="Enter new password" required>
    <button name="reset">Reset Password</button>
    <?= $message ?>
</form>
