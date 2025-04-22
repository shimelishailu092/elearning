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
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $student_name = mysqli_real_escape_string($conn, $_POST['student_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $department_code = mysqli_real_escape_string($conn, $_POST['department_code']);
    $edit_id = $_POST['edit_id'];

    if (!empty($student_id) && !empty($student_name) && !empty($email) && !empty($department_code)) {
        if ($edit_id) {
            // Update
            $query = "UPDATE students SET student_id='$student_id', student_name='$student_name', email='$email', department_code='$department_code' WHERE id=$edit_id";
            mysqli_query($conn, $query);
            $message = "✅ Student updated successfully.";
        } else {
            // Check if student_id or email exists
            $check = mysqli_query($conn, "SELECT * FROM students WHERE student_id='$student_id' OR email='$email'");
            if (mysqli_num_rows($check) > 0) {
                $message = "❌ Student ID or Email already exists.";
            } else {
                $query = "INSERT INTO students (student_id, student_name, email, department_code) 
                          VALUES ('$student_id', '$student_name', '$email', '$department_code')";
                mysqli_query($conn, $query);
                $message = "✅ Student added successfully.";
            }
        }
    }
}

// Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM students WHERE id=$id");
    header("Location: manage_student.php");
    exit();
}

// Edit
$edit_mode = false;
$edit_data = ['id' => '', 'student_id' => '', 'student_name' => '', 'email' => '', 'department_code' => ''];

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM students WHERE id=$id");
    if (mysqli_num_rows($res) == 1) {
        $edit_data = mysqli_fetch_assoc($res);
        $edit_mode = true;
    }
}

// Fetch all students
$result = mysqli_query($conn, "SELECT * FROM students ORDER BY id DESC");

// Fetch departments for dropdown
$departments = mysqli_query($conn, "SELECT * FROM departments");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Students</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="mb-4"><?= $edit_mode ? '✏️ Edit Student' : '👨‍🎓 Add Student' ?></h3>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" class="mb-4">
        <input type="hidden" name="edit_id" value="<?= $edit_data['id'] ?>">
        <div class="form-row">
            <div class="form-group col-md-3">
                <input type="text" name="student_id" class="form-control" placeholder="Student ID" value="<?= $edit_data['student_id'] ?>" required>
            </div>
            <div class="form-group col-md-3">
                <input type="text" name="student_name" class="form-control" placeholder="Full Name" value="<?= $edit_data['student_name'] ?>" required>
            </div>
            <div class="form-group col-md-3">
                <input type="email" name="email" class="form-control" placeholder="Email" value="<?= $edit_data['email'] ?>" required>
            </div>
            <div class="form-group col-md-2">
                <select name="department_code" class="form-control" required>
                    <option value="">-- Department --</option>
                    <?php while ($dept = mysqli_fetch_assoc($departments)): ?>
                        <option value="<?= $dept['department_code'] ?>" <?= $edit_data['department_code'] == $dept['department_code'] ? 'selected' : '' ?>>
                            <?= $dept['department_code'] ?> - <?= $dept['department_name'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group col-md-1">
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
            <th>Student ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Department</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['student_id']) ?></td>
                <td><?= htmlspecialchars($row['student_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['department_code']) ?></td>
                <td>
                    <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this student?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
