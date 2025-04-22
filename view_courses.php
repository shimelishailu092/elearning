<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

$query = "SELECT courses.*, departments.department_name AS department_name 
          FROM courses 
          LEFT JOIN departments ON courses.department_code = departments.department_code";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Courses</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="p-4">
    <div class="container">
        <h2 class="mb-4">Courses List</h2>
        <div class="mb-3">
            <a href="home.php" class="btn btn-primary">← Back to Home</a>
            <a href="manage_course.php" class="btn btn-secondary">← Back to Manage Courses</a>
        </div>
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Course Title</th>
                    <th>Code</th>
                    <th>Department</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= htmlspecialchars($row['course_title']); ?></td>
                    <td><?= htmlspecialchars($row['course_code']); ?></td>
                    <td><?= htmlspecialchars($row['department_name'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($row['description'] ?? 'N/A'); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
