<?php
session_start();
include 'db.php';
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit();
}
$result = mysqli_query($conn, "SELECT * FROM students");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">👨‍🎓 Registered Students</h2>
    <table class="table table-bordered bg-white">
        <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>Student Name</th>
            <th>Email</th>
            <th>Department Code</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['student_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['department_code']) ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <a href="home.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
</div>
</body>
</html>
