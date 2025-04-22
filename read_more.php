<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head><title>Read More</title></head>
<body>
<h2>More Info</h2>
<p>This e-learning system allows the admin to manage students, instructors, departments, courses, and faculty members.</p>
<a href="home.php">← Back to Home</a>
</body>
</html>
