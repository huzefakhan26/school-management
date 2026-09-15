<?php
// config.php - Database Connection & Session Bootstrap
$host     = "localhost";
$db_user  = "root";
$db_pass  = "";
$database = "school_management";

$conn = mysqli_connect($host, $db_user, $db_pass, $database);

if (!$conn) {
    // Don't leak DB internals to visitors
    die("Database connection failed. Please try again later.");
}

mysqli_set_charset($conn, "utf8mb4");

// Start session securely if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
    ]);
}
