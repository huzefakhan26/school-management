<?php
// search.php - Secure Prepared Search Script (public directory)
require_once 'config.php';

$is_admin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

$search_query = trim($_GET['query'] ?? '');
$results = null;

if (!empty($search_query)) {
    $sql = "SELECT id, student_name, roll_no, email, class_name, section, gender, created_at 
            FROM students 
            WHERE student_name LIKE ? OR roll_no LIKE ? OR class_name LIKE ? 
            ORDER BY id DESC";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        $param = "%" . $search_query . "%";
        mysqli_stmt_bind_param($stmt, "sss", $param, $param, $param);
        mysqli_stmt_execute($stmt);
        $results = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Bright Future Public School</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div class="nav-container">
            <div class="logo">🏫 Bright Future Public School</div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="register.php">Register Student</a></li>
                    <li><a href="search.php" class="active">Student Directory</a></li>
                    <li><a href="student_login.php">Student Login</a></li>
                    <li><a href="about.php">About School</a></li>
                    <?php if ($is_admin): ?>
                        <li><a href="admin_directory.php">Admin Dashboard</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <h2>Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h2>
        </div>

        <form action="search.php" method="GET" class="search-bar-form">
            <input type="text" name="query" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search by Student Name, Roll Number, or Class..." required>
            <button type="submit" class="btn">Search Records</button>
        </form>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Roll No</th>
                        <th>Email</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Gender</th>
                        <th>Registration Date</th>
                        <?php if ($is_admin): ?><th>Actions</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($results && mysqli_num_rows($results) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($results)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['roll_no']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['section']); ?></td>
                                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                <?php if ($is_admin): ?>
                                <td class="actions-cell">
                                    <a href="edit.php?id=<?php echo (int) $row['id']; ?>" class="btn-sm btn-edit">Edit</a>
                                    <a href="delete.php?id=<?php echo (int) $row['id']; ?>" class="btn-sm btn-delete" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?php echo $is_admin ? 9 : 8; ?>" style="text-align: center; padding: 2rem; color: var(--text-light);">
                                <?php echo empty($search_query) ? 'Enter a name, roll number, or class above to search the directory.' : 'No matching student records found.'; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Bright Future Public School.</p>
    </footer>

</body>
</html>
<?php mysqli_close($conn); ?>
