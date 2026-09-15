<?php
// edit.php - Edit existing student record (Admin restricted)
require_once 'config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

$error_message = "";
$student = null;
$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header("Location: admin_directory.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name  = trim($_POST['student_name'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $phone         = trim($_POST['phone'] ?? '');
    $class_name    = trim($_POST['class_name'] ?? '');
    $section       = trim($_POST['section'] ?? '');
    $parent_name   = trim($_POST['parent_name'] ?? '');
    $address       = trim($_POST['address'] ?? '');

    $allowed_classes  = ['6th', '7th', '8th', '9th', '10th'];
    $allowed_sections = ['A', 'B', 'C'];

    if (empty($student_name) || empty($email) || empty($phone) || empty($class_name) || empty($section) || empty($parent_name) || empty($address)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error_message = "Phone number must be exactly 10 digits.";
    } elseif (!in_array($class_name, $allowed_classes, true)) {
        $error_message = "Invalid class selected.";
    } elseif (!in_array($section, $allowed_sections, true)) {
        $error_message = "Invalid section selected.";
    } else {
        $sql = "UPDATE students SET student_name = ?, email = ?, phone = ?, class_name = ?, section = ?, parent_name = ?, address = ? WHERE id = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "sssssssi", $student_name, $email, $phone, $class_name, $section, $parent_name, $address, $id);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: admin_directory.php?msg=updated");
                exit();
            } else {
                $error_message = "Failed to update record.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

$sql = "SELECT * FROM students WHERE id = ?";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $student = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

if (!$student) {
    header("Location: admin_directory.php");
    exit();
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Bright Future Public School</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div class="nav-container">
            <div class="logo">🏫 Bright Future Public School</div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="admin_directory.php">All Students Directory</a></li>
                    <li><a href="register.php">Add Student</a></li>
                    <li><a href="admin_logout.php" style="background: var(--danger-color); color: white;">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="form-card">
            <h2>Edit Student Record (Admin Mode)</h2>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <form action="edit.php?id=<?php echo (int) $id; ?>" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="student_name">Student Full Name *</label>
                        <input type="text" id="student_name" name="student_name" value="<?php echo htmlspecialchars($student['student_name']); ?>" required maxlength="100">
                    </div>

                    <div class="form-group">
                        <label for="roll_no">Roll Number (Read-only)</label>
                        <input type="text" id="roll_no" value="<?php echo htmlspecialchars($student['roll_no']); ?>" disabled style="background-color: #e2e8f0; cursor: not-allowed;">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required maxlength="100">
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($student['phone']); ?>" required pattern="[0-9]{10}" maxlength="15">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="class_name">Class *</label>
                        <select id="class_name" name="class_name" required>
                            <option value="6th" <?php if($student['class_name'] == '6th') echo 'selected'; ?>>6th Standard</option>
                            <option value="7th" <?php if($student['class_name'] == '7th') echo 'selected'; ?>>7th Standard</option>
                            <option value="8th" <?php if($student['class_name'] == '8th') echo 'selected'; ?>>8th Standard</option>
                            <option value="9th" <?php if($student['class_name'] == '9th') echo 'selected'; ?>>9th Standard</option>
                            <option value="10th" <?php if($student['class_name'] == '10th') echo 'selected'; ?>>10th Standard</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="section">Section *</label>
                        <select id="section" name="section" required>
                            <option value="A" <?php if($student['section'] == 'A') echo 'selected'; ?>>Section A</option>
                            <option value="B" <?php if($student['section'] == 'B') echo 'selected'; ?>>Section B</option>
                            <option value="C" <?php if($student['section'] == 'C') echo 'selected'; ?>>Section C</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="parent_name">Parent / Guardian Name *</label>
                        <input type="text" id="parent_name" name="parent_name" value="<?php echo htmlspecialchars($student['parent_name']); ?>" required maxlength="100">
                    </div>

                    <div class="form-group">
                        <label for="address">Residential Address *</label>
                        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($student['address']); ?>" required maxlength="255">
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <button type="submit" class="btn" style="flex: 1;">Update Student Record</button>
                    <a href="admin_directory.php" class="btn btn-outline" style="background-color: #64748b; border: none; color: white; text-align: center; flex: 1; display: flex; align-items: center; justify-content: center;">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Bright Future Public School.</p>
    </footer>

</body>
</html>
