<?php
// materials.php - Study Material Directory
require_once 'config.php';

$class_filter = trim($_GET['class_name'] ?? '');
$allowed_classes = ['6th', '7th', '8th', '9th', '10th'];

if (!empty($class_filter) && in_array($class_filter, $allowed_classes, true)) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM study_materials WHERE class_name = ? ORDER BY id DESC");
    mysqli_stmt_bind_param($stmt, "s", $class_filter);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
} else {
    $class_filter = ''; // ignore anything that isn't a recognized class
    $result = mysqli_query($conn, "SELECT * FROM study_materials ORDER BY id DESC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Study Material Directory - Bright Future Public School</title>
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
                    <li><a href="admission.php">Admissions & Fees</a></li>
                    <li><a href="materials.php" class="active">Study Materials</a></li>
                    <li><a href="search.php">Student Directory</a></li>
                    <li><a href="student_login.php">Student Login</a></li>
                    <li><a href="admin_login.php" style="background: var(--accent-color); color: white;">Admin Portal</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <h2>📚 Study Material Directory</h2>
            <form action="materials.php" method="GET" style="display: flex; gap: 0.5rem; align-items: center;">
                <select name="class_name" style="padding: 0.5rem; border-radius: var(--radius); border: 1px solid var(--border-color);">
                    <option value="">All Classes</option>
                    <option value="6th" <?php if($class_filter=='6th') echo 'selected'; ?>>6th Standard</option>
                    <option value="7th" <?php if($class_filter=='7th') echo 'selected'; ?>>7th Standard</option>
                    <option value="8th" <?php if($class_filter=='8th') echo 'selected'; ?>>8th Standard</option>
                    <option value="9th" <?php if($class_filter=='9th') echo 'selected'; ?>>9th Standard</option>
                    <option value="10th" <?php if($class_filter=='10th') echo 'selected'; ?>>10th Standard</option>
                </select>
                <button type="submit" class="btn" style="padding: 0.5rem 1rem;">Filter</button>
            </form>
        </div>

        <div class="features-grid">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="card">
                        <h3>📖 <?php echo htmlspecialchars($row['title']); ?></h3>
                        <p style="margin-bottom: 0.5rem;"><strong>Class:</strong> <?php echo htmlspecialchars($row['class_name']); ?> | <strong>Subject:</strong> <?php echo htmlspecialchars($row['subject']); ?></p>
                        <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 1rem;">Uploaded by: <?php echo htmlspecialchars($row['uploaded_by']); ?> on <?php echo htmlspecialchars($row['created_at']); ?></p>
                        <a href="#" class="btn-sm btn" onclick="alert('Downloading study material document...'); return false;">Download <?php echo htmlspecialchars($row['file_type']); ?></a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="grid-column: 1 / -1; text-align: center; color: var(--text-light); padding: 3rem;">No study materials found for this selection.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Bright Future Public School. All rights reserved.</p>
    </footer>

</body>
</html>
<?php mysqli_close($conn); ?>
