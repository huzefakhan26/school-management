<?php
// student_login.php - Student Login to View Own Record
session_start();
require_once 'config.php';

// Handle logout
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    $_SESSION = array();
    session_destroy();
    header("Location: student_login.php?msg=logged_out");
    exit();
}

$error = "";
$notice = "";

if (isset($_GET['msg']) && $_GET['msg'] === 'logged_out') {
    $notice = "You have been logged out successfully.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $roll_no  = trim($_POST['roll_no'] ?? '');
    $password = (string) ($_POST['password'] ?? '');

    if (empty($roll_no) || empty($password)) {
        $error = "Please enter both Roll Number and Password.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT * FROM students WHERE roll_no = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $roll_no);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);

            if ($row && password_verify($password, $row['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['student_logged_in'] = true;
                $_SESSION['student_roll']       = $row['roll_no'];
                $_SESSION['student_name']       = $row['student_name'];
                mysqli_close($conn);
                header("Location: my_directory.php");
                exit();
            } else {
                $error = "Invalid Roll Number or Password.";
            }
        }
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Login - Bright Future Public School</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="nav-container">
            <div class="logo">🏫 Bright Future Public School</div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="student_login.php" class="active">Student Portal</a></li>
                    <li><a href="admin_login.php">Admin Portal</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="form-card" style="max-width: 450px;">
            <h2>Student Portal Login</h2>
            <p style="text-align: center; color: var(--text-light); margin-bottom: 1.5rem;">View your personal academic directory</p>

            <?php if (!empty($notice)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($notice); ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="student_login.php" method="POST">
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="roll_no">Roll Number</label>
                    <input type="text" id="roll_no" name="roll_no" required placeholder="e.g. BFS-101">
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn" style="width: 100%;">View My Directory</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Bright Future Public School.</p>
    </footer>
</body>
</html>
