<?php
// delete.php - Secure delete script for admin
require_once 'config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET['id'] ?? null;

if ($id && is_numeric($id)) {
    $sql = "DELETE FROM students WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);
header("Location: admin_directory.php?msg=deleted");
exit();
?>
