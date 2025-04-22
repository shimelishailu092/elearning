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
    $course_code = mysqli_real_escape_string($conn, $_POST['course_code']);
    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
    $department_code = mysqli_real_escape_string($conn, $_POST['department_code']);
    $edit_id = $_POST['edit_id'];

    if (!empty($course_code) && !empty($course_name)) {
        if ($edit_id) {
            // Update course
            $query = "UPDATE courses SET course_code='$course_code', course_name='$course_name', department_code='$department_code' WHERE id=$edit_id";
            mysqli_query($conn, $query);
            $message = "✅ Course updated successfully.";
        } else {
            // Check if course_code already exists
            $check = mysqli_query($conn, "SELECT * FROM courses WHERE course_code='$course_code'");
            if (mysqli_num_rows($check) > 0) {
                $message = "❌ Course code already exists.";
            } else {
                $query = "INSERT INTO courses (course_code, course_name, department_code) VALUES ('$course_code', '$course_name', '$department_code')";
                mysqli_query($conn, $query);
                $message = "✅ Course added successfully.";
            }
        }
    }
}

// Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM courses WHERE id=$id");
    header("Location: manage_course.php");
    exit();
}

// Edit
$edit_mode = false;
$edit_data = ['id' => '', 'course_code' => '', 'course_name' => '', 'department_code' => ''];

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM courses WHERE id=$id");
    if (mysqli_num_rows($res) == 1) {
        $edit_data = mysqli_fetch_assoc($res);
        $edit_mode = true;
    }
}

// Fetch all courses
$result = mysqli_query($conn, "SELECT * FROM courses ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Courses</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="mb-4"><?= $edit_mode ? '✏️ Edit Course' : '📘 Add Course' ?></h3>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" class="mb-4">
        <input type="hidden" name="edit_id" value="<?= $edit_data['id'] ?>">
        <div class="form-row">
            <div class="form-group col-md-3">
                <input type="text" name="course_code" class="form-control" placeholder="Course Code" value="<?= $edit_data['course_code'] ?>" required>
            </div>
            <div class="form-group col-md-4">
                <input type="text" name="course_name" class="form-control" placeholder="Course Name" value="<?= $edit_data['course_name'] ?>" required>
            </div>
            <div class="form-group col-md-3">
            <select name="department_code" class="form-control" required>
    <option value="">-- Select Department --</option>
    <?php
    $dept_res = mysqli_query($conn, "SELECT * FROM departments");
    while ($dept = mysqli_fetch_assoc($dept_res)):
    ?>
        <option value="<?= $dept['department_code'] ?>" <?= $edit_data['department_code'] == $dept['department_code'] ? 'selected' : '' ?>>
            <?= $dept['department_code'] ?> - <?= $dept['department_name'] ?>
        </option>
    <?php endwhile; ?>
</select>

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
            <th>Course Code</th>
            <th>Course Name</th>
            <th>Department Code</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['course_code']) ?></td>
                <td><?= htmlspecialchars($row['course_name']) ?></td>
                <td><?= htmlspecialchars($row['department_code']) ?></td>
                <td>
                    <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Update</a>
                    <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
