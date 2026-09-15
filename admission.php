<?php
// admission.php - Admission Records & Application Fees Management
require_once 'config.php';

$success = "";
$error = "";
$is_admin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_application'])) {
    $applicant_name = trim($_POST['applicant_name'] ?? '');
    $applied_class  = trim($_POST['applied_class'] ?? '');
    $guardian_name  = trim($_POST['guardian_name'] ?? '');
    $contact_phone  = trim($_POST['contact_phone'] ?? '');
    $fee_amount     = 500.00;
    $transaction_id = trim($_POST['transaction_id'] ?? '') ?: ('TXN' . random_int(100000, 999999));
    $payment_status = "Paid";

    $allowed_classes = ['6th', '7th', '8th', '9th', '10th'];

    if (empty($applicant_name) || empty($applied_class) || empty($guardian_name) || empty($contact_phone)) {
        $error = "All fields are required for admission application.";
    } elseif (!in_array($applied_class, $allowed_classes, true)) {
        $error = "Invalid class selected.";
    } elseif (!preg_match('/^[0-9]{10}$/', $contact_phone)) {
        $error = "Contact phone must be exactly 10 digits.";
    } else {
        $sql = "INSERT INTO admissions (applicant_name, applied_class, guardian_name, contact_phone, fee_amount, payment_status, transaction_id, admission_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'Under Review')";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssdss", $applicant_name, $applied_class, $guardian_name, $contact_phone, $fee_amount, $payment_status, $transaction_id);
            if (mysqli_stmt_execute($stmt)) {
                $success = "Admission application submitted successfully! Application Fee (₹500) received via Transaction ID: " . htmlspecialchars($transaction_id) . ".";
            } else {
                $error = "Failed to submit admission application.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// Only admins can see the full admissions ledger (contains guardian phone numbers, etc.)
$admissions_result = null;
if ($is_admin) {
    $admissions_result = mysqli_query($conn, "SELECT * FROM admissions ORDER BY id DESC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admissions & Fees - Bright Future Public School</title>
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
                    <li><a href="admission.php" class="active">Admissions & Fees</a></li>
                    <li><a href="materials.php">Study Materials</a></li>
                    <li><a href="search.php">Student Directory</a></li>
                    <li><a href="student_login.php">Student Login</a></li>
                    <li><a href="admin_login.php" style="background: var(--accent-color); color: white;">Admin Portal</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="form-card" style="margin-bottom: 2.5rem;">
            <h2>📋 Admission Application & Fee Payment</h2>
            <p style="text-align: center; color: var(--text-light); margin-bottom: 1.5rem;">
                New applicants can submit their admission details and securely pay the mandatory ₹500 application processing fee.
            </p>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="admission.php" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="applicant_name">Applicant Full Name *</label>
                        <input type="text" id="applicant_name" name="applicant_name" required placeholder="e.g. Rahul Sharma">
                    </div>
                    <div class="form-group">
                        <label for="applied_class">Applying for Class *</label>
                        <select id="applied_class" name="applied_class" required>
                            <option value="">Select Class</option>
                            <option value="6th">6th Standard</option>
                            <option value="7th">7th Standard</option>
                            <option value="8th">8th Standard</option>
                            <option value="9th">9th Standard</option>
                            <option value="10th">10th Standard</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="guardian_name">Guardian Name *</label>
                        <input type="text" id="guardian_name" name="guardian_name" required placeholder="Parent or Guardian Name">
                    </div>
                    <div class="form-group">
                        <label for="contact_phone">Contact Phone *</label>
                        <input type="text" id="contact_phone" name="contact_phone" required pattern="[0-9]{10}" placeholder="10-digit mobile number">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="fee_display">Application Fee Amount</label>
                        <input type="text" id="fee_display" value="₹500.00 (Standard Processing Fee)" disabled style="background: #e2e8f0;">
                    </div>
                    <div class="form-group">
                        <label for="transaction_id">Simulated Payment ID / UPI Ref</label>
                        <input type="text" id="transaction_id" name="transaction_id" value="TXN<?php echo random_int(1000000, 9999999); ?>" readonly style="background: #e2e8f0;">
                    </div>
                </div>

                <button type="submit" name="submit_application" class="btn" style="width: 100%;">Pay ₹500 & Submit Application</button>
            </form>
        </div>

        <?php if ($is_admin): ?>
            <h2>📂 Admission Records & Fee Status Directory (Admin View)</h2>
            <p style="color: var(--text-light); margin-bottom: 1rem;">Tracking all submitted applications and payment verifications.</p>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Applicant Name</th>
                            <th>Class</th>
                            <th>Guardian</th>
                            <th>Phone</th>
                            <th>Fee Paid</th>
                            <th>Transaction ID</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($admissions_result && mysqli_num_rows($admissions_result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($admissions_result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><strong><?php echo htmlspecialchars($row['applicant_name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['applied_class']); ?></td>
                                    <td><?php echo htmlspecialchars($row['guardian_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['contact_phone']); ?></td>
                                    <td>₹<?php echo number_format($row['fee_amount'], 2); ?></td>
                                    <td><code><?php echo htmlspecialchars($row['transaction_id'] ?? 'N/A'); ?></code></td>
                                    <td>
                                        <span class="badge badge-success"><?php echo htmlspecialchars($row['admission_status']); ?></span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 2rem; color: var(--text-light);">No admission records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="form-card" style="max-width: 700px; text-align: center;">
                <p style="color: var(--text-light);">The full admissions ledger (guardian contact details and payment status) is visible to school administrators only. <a href="admin_login.php">Admin login</a></p>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Bright Future Public School. All rights reserved.</p>
    </footer>

</body>
</html>
<?php mysqli_close($conn); ?>
