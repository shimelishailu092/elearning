<?php
session_start();
include 'db.php';

// Only allow logged-in admins
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit();
}

// Message holder
$message = '';

// Handle add or update
if (isset($_POST['save'])) {
    $department_code = mysqli_real_escape_string($conn, $_POST['department_code']);
    $department_name = mysqli_real_escape_string($conn, $_POST['department_name']);
    $faculty_code = mysqli_real_escape_string($conn, $_POST['faculty_code']);
    $edit_id = $_POST['edit_id'];

    if (!empty($department_code) && !empty($department_name)) {
        if ($edit_id) {
            // Update department
            $query = "UPDATE departments SET department_code='$department_code', 
            department_name='$department_name', faculty_code='$faculty_code' WHERE id=$edit_id";
            mysqli_query($conn, $query);
            $message = "✅ Department updated successfully.";
        } else {
            // Check if department_code already exists
            $check = mysqli_query($conn, "SELECT * FROM departments WHERE department_code='$department_code'");
            if (mysqli_num_rows($check) > 0) {
                $message = "❌ Department code already exists.";
            } else {
                $query = "INSERT INTO departments (department_code, department_name, 
                faculty_code) VALUES ('$department_code', '$department_name', '$faculty_code')";
                mysqli_query($conn, $query);
                $message = "✅ Department added successfully.";
            }
        }
    }
}

// Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM departments WHERE id=$id");
    header("Location: manage_department.php");
    exit();
}

// Edit
$edit_mode = false;
$edit_data = ['id' => '', 'department_code' => '', 'department_name' => '', 'faculty_code' => ''];

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM departments WHERE id=$id");
    if (mysqli_num_rows($res) == 1) {
        $edit_data = mysqli_fetch_assoc($res);
        $edit_mode = true;
    }
}

// Fetch all departments
$result = mysqli_query($conn, "SELECT * FROM departments ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Departments</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="mb-4"><?= $edit_mode ? '✏️ Edit Department' : '📘 Add Department' ?></h3>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" class="mb-4">
        <input type="hidden" name="edit_id" value="<?= $edit_data['id'] ?>">
        <div class="form-row">
            <div class="form-group col-md-3">
                <input type="text" name="department_code" class="form-control" 
                placeholder="Department Code" value="<?= $edit_data['department_code'] ?>" required>
            </div>
            <div class="form-group col-md-4">
                <input type="text" name="department_name" class="form-control" 
                placeholder="Department Name" value="<?= $edit_data['department_name'] ?>" required>
            </div>
            <div class="form-group col-md-3">
                <input type="text" name="faculty_code" class="form-control" 
                placeholder="Faculty Code" value="<?= $edit_data['faculty_code'] ?>">
            </div>
            <div class="form-group col-md-2">
                <button type="submit" name="save" class="btn btn-primary w-100"><?= $edit_mode ? 'Update' : 'Add' ?></button>
            </div>
        </div>
        <a href="home.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
    </form>

    <table class="table table-bordered bg-white">
        <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th>Department Code</th>
            <th>Department Name</th>
            <th>Faculty Code</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['department_code']) ?></td>
                <td><?= htmlspecialchars($row['department_name']) ?></td>
                <td><?= htmlspecialchars($row['faculty_code']) ?></td>
                <td>
                    <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" 
                    onclick="return confirm('Are you sure you want to delete this department?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
