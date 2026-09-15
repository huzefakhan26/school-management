<?php
// register.php - Comprehensive Student Signup Form
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Signup - Bright Future Public School</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div class="nav-container">
            <div class="logo">🏫 Bright Future Public School</div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="register.php" class="active">Student Signup</a></li>
                    <li><a href="admission.php">Admissions & Fees</a></li>
                    <li><a href="materials.php">Study Materials</a></li>
                    <li><a href="search.php">Student Directory</a></li>
                    <li><a href="student_login.php">Student Login</a></li>
                    <li><a href="admin_login.php" style="background: var(--accent-color); color: white;">Admin Portal</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="form-card">
            <h2>Student Registration / Signup Form</h2>
            <p style="text-align: center; color: var(--text-light); margin-bottom: 1.5rem;">Fill out the complete information below to register the student in our official database.</p>

            <form action="save.php" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="student_name">Student Full Name *</label>
                        <input type="text" id="student_name" name="student_name" required minlength="2" maxlength="100" placeholder="e.g. Aarav Sharma">
                    </div>

                    <div class="form-group">
                        <label for="roll_no">Roll Number / Student ID *</label>
                        <input type="text" id="roll_no" name="roll_no" required maxlength="30" placeholder="e.g. BFS-106">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required maxlength="100" placeholder="student@example.com">
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="text" id="phone" name="phone" required pattern="[0-9]{10}" maxlength="15" placeholder="10-digit mobile number">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Login Password *</label>
                        <input type="password" id="password" name="password" required minlength="8" maxlength="72" placeholder="At least 8 characters">
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password *</label>
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="8" maxlength="72" placeholder="Re-enter password">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="class_name">Class / Standard *</label>
                        <select id="class_name" name="class_name" required>
                            <option value="">Select Class</option>
                            <option value="6th">6th Standard</option>
                            <option value="7th">7th Standard</option>
                            <option value="8th">8th Standard</option>
                            <option value="9th">9th Standard</option>
                            <option value="10th">10th Standard</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="section">Section *</label>
                        <select id="section" name="section" required>
                            <option value="">Select Section</option>
                            <option value="A">Section A</option>
                            <option value="B">Section B</option>
                            <option value="C">Section C</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="dob">Date of Birth *</label>
                        <input type="date" id="dob" name="dob" required>
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender *</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="parent_name">Parent / Guardian Name *</label>
                        <input type="text" id="parent_name" name="parent_name" required maxlength="100" placeholder="e.g. Rajesh Sharma">
                    </div>

                    <div class="form-group">
                        <label for="address">Residential Address *</label>
                        <input type="text" id="address" name="address" required maxlength="255" placeholder="House No, Street, City">
                    </div>
                </div>

                <button type="submit" class="btn" style="width: 100%; margin-top: 1rem;">Complete Student Registration</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Bright Future Public School. All rights reserved.</p>
    </footer>

</body>
</html>
