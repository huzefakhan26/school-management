<?php
// my_directory.php - Displays only the logged-in student's own record
session_start();
if (!isset($_SESSION['student_logged_in']) || $_SESSION['student_logged_in'] !== true) {
    header("Location: student_login.php");
    exit();
}

require_once 'config.php';
$roll_no = $_SESSION['student_roll'];

$stmt = mysqli_prepare($conn, "SELECT * FROM students WHERE roll_no = ?");
mysqli_stmt_bind_param($stmt, "s", $roll_no);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
mysqli_close($conn);

// If the account backing this session no longer exists, force re-login
if (!$student) {
    $_SESSION = array();
    session_destroy();
    header("Location: student_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Directory - Bright Future Public School</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="nav-container">
            <div class="logo">🏫 Student Profile Portal</div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="my_directory.php" class="active">My Profile</a></li>
                    <li><a href="student_login.php?logout=true" style="color: #fca5a5;">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="form-card" style="max-width: 700px;">
            <h2>Welcome, <?php echo htmlspecialchars($student['student_name']); ?>!</h2>
            <p style="text-align: center; color: var(--text-light); margin-bottom: 2rem;">Your official student directory record</p>

            <div class="table-responsive">
                <table>
                    <tr>
                        <th>Roll Number</th>
                        <td><?php echo htmlspecialchars($student['roll_no']); ?></td>
                    </tr>
                    <tr>
                        <th>Full Name</th>
                        <td><?php echo htmlspecialchars($student['student_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Email Address</th>
                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                    </tr>
                    <tr>
                        <th>Phone Number</th>
                        <td><?php echo htmlspecialchars($student['phone']); ?></td>
                    </tr>
                    <tr>
                        <th>Class & Section</th>
                        <td><?php echo htmlspecialchars($student['class_name'] . ' - ' . $student['section']); ?></td>
                    </tr>
                    <tr>
                        <th>Date of Birth</th>
                        <td><?php echo htmlspecialchars($student['dob']); ?></td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td><?php echo htmlspecialchars($student['gender']); ?></td>
                    </tr>
                    <tr>
                        <th>Parent/Guardian Name</th>
                        <td><?php echo htmlspecialchars($student['parent_name']); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Bright Future Public School.</p>
    </footer>
</body>
</html>
