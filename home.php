<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

// Fetch counts from DB
$studentCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM students"))['total'];
$instructorCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM instructors"))['total'];
$departmentCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM departments"))['total'];
$courseCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM courses"))['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Home - E-Learning</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            display: flex;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #343a40;
            color: white;
            position: fixed;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            padding: 10px;
            display: block;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
        }
        .card {
            margin-bottom: 20px;
        }
        .top-bar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-center">Admin page</h4>
    <a href="home.php">Home</a>
    <a href="manage_student.php">Manage Student</a>
    <a href="manage_instructor.php">Manage Instructor</a>
    <a href="manage_faculty.php">Manage Faculty</a>
    <a href="manage_department.php">Manage Department</a>
    <a href="manage_course.php">Manage Course</a>
</div>

<!-- Content -->
<div class="content">
    <!-- Top right logout -->
    <div class="top-bar">
        <span class="mr-3">Welcome, <?= $_SESSION['admin_username']; ?> |</span>
        <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>

    <h2>E-learning <small class="text-muted">Admin page</small></h2>

    <div class="alert alert-info">
        <strong>Welcome</strong> to elearning admin page
    </div>

    <div class="row text-white">
        <div class="col-md-3">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4><?php echo $studentCount; ?> students</h4>
                    <a href="view_students.php" class="btn btn-light btn-sm">View Details</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success">
                <div class="card-body">
                    <h4><?php echo $instructorCount; ?> Instructors</h4>
                    <a href="view_instructors.php" class="btn btn-light btn-sm">View Instructor</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success">
                <div class="card-body">
                    <h4><?php echo $departmentCount; ?> Departments</h4>
                    <a href="view_departments.php" class="btn btn-light btn-sm">View Departments</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info">
                <div class="card-body">
                    <h4><?php echo $courseCount; ?> Registered Courses</h4>
                    <a href="view_courses.php" class="btn btn-light btn-sm">View Courses</a>
                </div>
            </div>
        </div>
    </div>

    <div class="jumbotron mt-4">
        <h1>Hello, ADMIN!</h1>
        <p>Welcome to the admin page</p>
        <a class="btn btn-primary btn-lg" href="read_more.php">Read more »</a>
    </div>
</div>

</body>
</html>
