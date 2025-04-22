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
    $instructor_id = mysqli_real_escape_string($conn, $_POST['instructor_id']);
    $instructor_name = mysqli_real_escape_string($conn, $_POST['instructor_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $department_code = mysqli_real_escape_string($conn, $_POST['department_code']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $edit_id = $_POST['edit_id'];

    if (!empty($instructor_id) && !empty($instructor_name) && !empty($email) && !empty($department_code)) {
        if ($edit_id) {
            // Update
            $query = "UPDATE instructors SET instructor_id='$instructor_id', instructor_name='$instructor_name', email='$email', department_code='$department_code', phone='$phone' WHERE id=$edit_id";
            mysqli_query($conn, $query);
            $message = "✅ Instructor updated successfully.";
        } else {
            // Check for duplicate ID or email
            $check = mysqli_query($conn, "SELECT * FROM instructors WHERE instructor_id='$instructor_id' OR email='$email'");
            if (mysqli_num_rows($check) > 0) {
                $message = "❌ Instructor ID or Email already exists.";
            } else {
                $query = "INSERT INTO instructors (instructor_id, instructor_name, email, department_code, phone) 
                VALUES ('$instructor_id', '$instructor_name', '$email', '$department_code', '$phone')";
                mysqli_query($conn, $query);
                $message = "✅ Instructor added successfully.";
            }
        }
    }
}

// Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM instructors WHERE id=$id");
    header("Location: manage_instructor.php");
    exit();
}

// Edit
$edit_mode = false;
$edit_data = ['id' => '', 'instructor_id' => '', 'instructor_name' => '', 'email' => '', 'department_code' => ''];

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM instructors WHERE id=$id");
    if (mysqli_num_rows($res) == 1) {
        $edit_data = mysqli_fetch_assoc($res);
        $edit_mode = true;
    }
}

// Fetch all instructors
$result = mysqli_query($conn, "SELECT * FROM instructors ORDER BY id DESC");

// Fetch departments
$departments = mysqli_query($conn, "SELECT * FROM departments");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Instructors</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="mb-4"><?= $edit_mode ? '✏️ Edit Instructor' : '👨‍🏫 Add Instructor' ?></h3>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" class="mb-4">
        <input type="hidden" name="edit_id" value="<?= $edit_data['id'] ?>">
        <div class="form-row">
            <div class="form-group col-md-3">
                <input type="text" name="instructor_id" class="form-control" placeholder="Instructor ID" value="<?= $edit_data['instructor_id'] ?>" required>
            </div>
            <div class="form-group col-md-3">
                <input type="text" name="instructor_name" class="form-control" placeholder="Full Name" value="<?= $edit_data['instructor_name'] ?>" required>
            </div>
            <div class="form-group col-md-3">
                <input type="email" name="email" class="form-control" placeholder="Email" value="<?= $edit_data['email'] ?>" required>
            </div> 
            <div class="form-group col-md-3">
          <input type="text" name="phone" class="form-control" placeholder="Phone Number" value="<?= htmlspecialchars($edit_data['phone'] ?? '') ?>">
           </div>
            <div class="form-group col-md-2">
                <select name="department_code" class="form-control" required>
                    <option value="">-- Department --</option>
                    <?php while ($dept = mysqli_fetch_assoc($departments)): ?>
                        <option value="<?= $dept['department_code'] ?>" 
                        <?= $edit_data['department_code'] == $dept['department_code'] ? 'selected' : '' ?>>
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
            <th>Instructor ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Department</th> 
            <th>Phone</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['instructor_id']) ?></td>
                <td><?= htmlspecialchars($row['instructor_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['department_code']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td>
                    <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this instructor?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
