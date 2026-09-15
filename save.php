<?php
// save.php - Process Student Registration
require_once 'config.php';

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name  = trim($_POST['student_name'] ?? '');
    $roll_no       = trim($_POST['roll_no'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $password         = (string) ($_POST['password'] ?? '');
    $confirm_password = (string) ($_POST['confirm_password'] ?? '');
    $phone         = trim($_POST['phone'] ?? '');
    $class_name    = trim($_POST['class_name'] ?? '');
    $section       = trim($_POST['section'] ?? '');
    $dob           = trim($_POST['dob'] ?? '');
    $gender        = trim($_POST['gender'] ?? '');
    $parent_name   = trim($_POST['parent_name'] ?? '');
    $address       = trim($_POST['address'] ?? '');

    $allowed_classes  = ['6th', '7th', '8th', '9th', '10th'];
    $allowed_sections = ['A', 'B', 'C'];
    $allowed_genders  = ['Male', 'Female', 'Other'];

    if (empty($student_name) || empty($roll_no) || empty($email) || empty($password) || empty($confirm_password) || empty($phone) ||
        empty($class_name) || empty($section) || empty($dob) || empty($gender) || empty($parent_name) || empty($address)) {
        $error_message = "All required fields must be filled.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format provided.";
    } elseif (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Password and Confirm Password do not match.";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error_message = "Phone number must be exactly 10 digits.";
    } elseif (!in_array($class_name, $allowed_classes, true)) {
        $error_message = "Invalid class selected.";
    } elseif (!in_array($section, $allowed_sections, true)) {
        $error_message = "Invalid section selected.";
    } elseif (!in_array($gender, $allowed_genders, true)) {
        $error_message = "Invalid gender selected.";
    } elseif (!DateTime::createFromFormat('Y-m-d', $dob)) {
        $error_message = "Invalid date of birth.";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO students (student_name, roll_no, email, password_hash, phone, class_name, section, dob, gender, parent_name, address) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "sssssssssss", $student_name, $roll_no, $email, $password_hash, $phone, $class_name, $section, $dob, $gender, $parent_name, $address);

            if (mysqli_stmt_execute($stmt)) {
                $success_message = "Student registered successfully and stored in the directory!";
            } else {
                if (mysqli_errno($conn) == 1062) {
                    $error_message = "Error: Roll Number '" . htmlspecialchars($roll_no) . "' already exists in the system.";
                } else {
                    $error_message = "Failed to register student due to a database error.";
                }
            }
            mysqli_stmt_close($stmt);
        } else {
            $error_message = "Database query preparation failed.";
        }
    }
} else {
    header("Location: register.php");
    exit();
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Status - Bright Future Public School</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div class="nav-container">
            <div class="logo">🏫 Bright Future Public School</div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="register.php">Student Signup</a></li>
                    <li><a href="search.php">Student Directory</a></li>
                    <li><a href="about.php">About School</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="form-card" style="text-align: center;">
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                <p style="margin-bottom: 1.5rem;">Full student info has been recorded and grouped under class records.</p>
                <div class="btn-group">
                    <a href="register.php" class="btn">Register Another Student</a>
                    <a href="search.php" class="btn" style="background-color: var(--primary-color); color: white;">View Student Directory</a>
                </div>
            <?php else: ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                <div style="margin-top: 1.5rem;">
                    <a href="register.php" class="btn">Back to Registration</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Bright Future Public School.</p>
    </footer>

</body>
</html>
