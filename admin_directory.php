<?php
// admin_directory.php - Accessible ONLY by Admin to view all student records
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

require_once 'config.php';

$class_filter = trim($_GET['class_filter'] ?? '');
if (!empty($class_filter)) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM students WHERE class_name LIKE ? ORDER BY class_name, roll_no ASC");
    $param = "%" . $class_filter . "%";
    mysqli_stmt_bind_param($stmt, "s", $param);
    mysqli_stmt_execute($stmt);
    $results = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
} else {
    $results = mysqli_query($conn, "SELECT * FROM students ORDER BY class_name, roll_no ASC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Bright Future Public School</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="nav-container">
            <div class="logo">🏫 Admin Dashboard</div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="admin_directory.php" class="active">All Students Directory</a></li>
                    <li><a href="register.php">Add Student</a></li>
                    <li><a href="admin_logout.php" style="color: #fca5a5;">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <h2>Complete School Student Directory (Admin View)</h2>
            <a href="register.php" class="btn">+ Register New Student</a>
        </div>

        <form action="admin_directory.php" method="GET" class="search-bar-form">
            <input type="text" name="class_filter" value="<?php echo htmlspecialchars($class_filter); ?>" placeholder="Filter by Class (e.g. 10th)..." required>
            <button type="submit" class="btn">Filter Class</button>
            <a href="admin_directory.php" class="btn btn-outline" style="background: #64748b; border:none; display:flex; align-items:center;">Reset</a>
        </form>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Roll No</th>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Class & Sec</th>
                        <th>Phone</th>
                        <th>Parent Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($results && mysqli_num_rows($results) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($results)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['roll_no']); ?></td>
                                <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['class_name'] . ' - ' . $row['section']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td><?php echo htmlspecialchars($row['parent_name']); ?></td>
                                <td class="actions-cell">
                                    <a href="edit.php?id=<?php echo (int) $row['id']; ?>" class="btn-sm btn-edit">Edit</a>
                                    <a href="delete.php?id=<?php echo (int) $row['id']; ?>" class="btn-sm btn-delete" onclick="return confirm('Delete this student record?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-light);">No student records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Bright Future Public School. Admin Panel.</p>
    </footer>
</body>
</html>
<?php mysqli_close($conn); ?>
