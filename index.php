<?php
// index.php - School Homepage
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bright Future Public School</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div class="nav-container">
            <div class="logo">🏫 Bright Future Public School</div>
            <nav>
                <ul>
                    <li><a href="index.php" class="active">Home</a></li>
                    <li><a href="register.php">Student Signup</a></li>
                    <li><a href="admission.php">Admissions & Fees</a></li>
                    <li><a href="materials.php">Study Materials</a></li>
                    <li><a href="search.php">Student Directory</a></li>
                    <li><a href="student_login.php">Student Login</a></li>
                    <li><a href="admin_login.php" style="background: var(--accent-color); color: white;">Admin Portal</a></li>
                    <li><a href="about.php">About School</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <section class="hero">
            <h1>Welcome to Bright Future Public School</h1>
            <p>Comprehensive Academic Management & Student Portal</p>
            <div class="btn-group">
                <a href="register.php" class="btn">Student Signup Form</a>
                <a href="search.php" class="btn btn-outline" style="border-color: white; color: white;">View Student Directory</a>
                <a href="admin_login.php" class="btn" style="background-color: var(--accent-color);">Secure Admin Login</a>
            </div>
        </section>

        <div class="features-grid">
            <div class="card">
                <h3>📝 Student Signup & Info</h3>
                <p>New students can register online with complete personal, parental, and contact details securely saved to MySQL.</p>
            </div>
            <div class="card">
                <h3>📂 Class-Wise Directory</h3>
                <p>Search the directory by name, roll number, or class. Admins get extra filtering, editing, and deletion tools.</p>
            </div>
            <div class="card">
                <h3>💳 Admissions & Fee Records</h3>
                <p>Manage admission applications, application fee payments, and verification tracking in one unified place.</p>
            </div>
            <div class="card">
                <h3>📊 Attendance & Marks</h3>
                <p>Track daily student attendance logs and academic performance grades across various subjects and exams.</p>
            </div>
            <div class="card">
                <h3>📚 Study Material Directory</h3>
                <p>Access and download class-wise notes, assignment sheets, and syllabus materials uploaded by faculty.</p>
            </div>
            <div class="card">
                <h3>🔒 Secure Admin Access</h3>
                <p>Protected administrative control with secure credentials ensuring data privacy and robust school governance.</p>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Bright Future Public School. All rights reserved.</p>
    </footer>

</body>
</html>
