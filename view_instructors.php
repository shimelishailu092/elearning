<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM instructors ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Instructors</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4 text-center">👨‍🏫 Registered Instructors</h2>

    <table class="table table-bordered bg-white shadow-sm">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Instructor Name</th>
                <th>Email</th>
                <th>Department Code</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($row['instructor_name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['department_code']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center text-muted">No instructors found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="text-center">
        <a href="home.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
    </div>
</div>
</body>
</html>
