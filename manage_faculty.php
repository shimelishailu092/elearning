<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit();
}

$message = '';

// Handle add/update
if (isset($_POST['save'])) {
    $faculty_code = mysqli_real_escape_string($conn, $_POST['faculty_code']);
    $faculty_name = mysqli_real_escape_string($conn, $_POST['faculty_name']);
    $department_code = mysqli_real_escape_string($conn, $_POST['department_code']);
    $edit_id = $_POST['edit_id'];

    if (!empty($faculty_code) && !empty($faculty_name) && !empty($department_code)) {
        if ($edit_id) {
            $query = "UPDATE faculties SET faculty_code='$faculty_code', faculty_name='$faculty_name', department_code='$department_code' WHERE id=$edit_id";
            mysqli_query($conn, $query);
            $message = "✅ Faculty updated successfully.";
        } else {
            $check = mysqli_query($conn, "SELECT * FROM faculties WHERE faculty_code='$faculty_code'");
            if (mysqli_num_rows($check) > 0) {
                $message = "❌ Faculty code already exists.";
            } else {
                $query = "INSERT INTO faculties (faculty_code, faculty_name, department_code) VALUES ('$faculty_code', '$faculty_name', '$department_code')";
                mysqli_query($conn, $query);
                $message = "✅ Faculty added successfully.";
            }
        }
    }
}

// Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM faculties WHERE id=$id");
    header("Location: manage_faculty.php");
    exit();
}

// Edit
$edit_mode = false;
$edit_data = ['id' => '', 'faculty_code' => '', 'faculty_name' => '', 'department_code' => ''];

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM faculties WHERE id=$id");
    if (mysqli_num_rows($res) == 1) {
        $edit_data = mysqli_fetch_assoc($res);
        $edit_mode = true;
    }
}

// Fetch all faculties
$result = mysqli_query($conn, "SELECT * FROM faculties ORDER BY id DESC");

// Fetch departments for dropdown
$departments = mysqli_query($conn, "SELECT * FROM departments");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Faculties</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="mb-4"><?= $edit_mode ? '✏️ Edit Faculty' : '🏫 Add Faculty' ?></h3>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" class="mb-4">
        <input type="hidden" name="edit_id" value="<?= $edit_data['id'] ?>">
        <div class="form-row">
            <div class="form-group col-md-3">
                <input type="text" name="faculty_code" class="form-control" placeholder="Faculty Code" value="<?= $edit_data['faculty_code'] ?>" required>
            </div>
            <div class="form-group col-md-4">
                <input type="text" name="faculty_name" class="form-control" placeholder="Faculty Name" value="<?= $edit_data['faculty_name'] ?>" required>
            </div>
            <div class="form-group col-md-3">
                <select name="department_code" class="form-control" required>
                    <option value="">-- Select Department --</option>
                    <?php while ($dept = mysqli_fetch_assoc($departments)): ?>
                        <option value="<?= $dept['department_code'] ?>" <?= $edit_data['department_code'] == $dept['department_code'] ? 'selected' : '' ?>>
                            <?= $dept['department_code'] ?> - <?= $dept['department_name'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group col-md-2">
                <button type="submit" name="save" class="btn btn-success w-100">
                    <?= $edit_mode ? 'Update' : 'Add' ?>
                </button>
            </div>
        </div>
        <a href="home.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
    </form>

    <table class="table table-bordered bg-white">
        <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th>Faculty Code</th>
            <th>Faculty Name</th>
            <th>Department Code</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['faculty_code']) ?></td>
                <td><?= htmlspecialchars($row['faculty_name']) ?></td>
                <td><?= htmlspecialchars($row['department_code']) ?></td>
                <td>
                    <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this faculty?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
