<?php
// admin_login.php - Secure Admin Authentication
require_once 'config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        $sql = "SELECT id, username, password_hash, full_name FROM admin_users WHERE username = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($admin = mysqli_fetch_assoc($result)) {
                // Verify password against secure hash only - no hardcoded backdoor
                if (password_verify($password, $admin['password_hash'])) {
                    session_regenerate_id(true);
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_username']  = $admin['username'];
                    $_SESSION['admin_name']      = $admin['full_name'];
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    header("Location: admin_directory.php");
                    exit();
                } else {
                    $error = "Invalid secure ID or password.";
                }
            } else {
                $error = "Invalid secure ID or password.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    $_SESSION = array();
    session_destroy();
    header("Location: admin_login.php?msg=logged_out");
    exit();
}

$notice = "";
if (isset($_GET['msg']) && $_GET['msg'] === 'logged_out') {
    $notice = "You have been logged out successfully.";
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Bright Future Public School</title>
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
                    <li><a href="admin_login.php" class="active">Admin Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="form-card" style="max-width: 450px; margin-top: 3rem;">
            <h2>🔐 Secure Admin Login</h2>
            <p style="text-align: center; color: var(--text-light); margin-bottom: 1.5rem; font-size: 0.9rem;">
                Authorized personnel only. Enter admin credentials to access student directory search, records update, attendance, and marks management.
            </p>

            <?php if (!empty($notice)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($notice); ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="admin_login.php" method="POST">
                <div class="form-group">
                    <label for="username">Admin Secure ID *</label>
                    <input type="text" id="username" name="username" required placeholder="Enter your admin ID">
                </div>

                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••">
                </div>

                <button type="submit" class="btn" style="width: 100%;">Login to Admin Portal</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Bright Future Public School. All rights reserved.</p>
    </footer>

</body>
</html>
