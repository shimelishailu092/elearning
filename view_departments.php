<?php
session_start();
include 'db.php';

// Only allow logged-in admins
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit();
}

// Fetch departments
$result = mysqli_query($conn, "SELECT * FROM departments ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Departments</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="mb-4">📋 List of Departments</h3>
    <a href="home.php" class="btn btn-secondary mb-3">⬅ Back to Dashboard</a>

    <table class="table table-bordered bg-white">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Department Code</th>
                <th>Department Name</th>
                <th>Faculty Code</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($row['department_code']) ?></td>
                    <td><?= htmlspecialchars($row['department_name']) ?></td>
                    <td><?= htmlspecialchars($row['faculty_code']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
