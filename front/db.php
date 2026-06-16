<?php
// db.php - Shared database connection for Blood Connect
// Default XAMPP credentials: host=localhost, user=root, password=(empty), db=bloodconnect

define('DB_HOST', 'localhost');
define('DB_NAME', 'bloodconnect');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_PORT', 3306);

// PDO connection (used by most pages)
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed. Please make sure XAMPP MySQL is running and the database exists.<br>Error: " . $e->getMessage());
}

// mysqli connection (used by some admin pages)
function db(): mysqli {
    static $conn = null;
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
        if ($conn->connect_error) {
            error_log('DB connect error: ' . $conn->connect_error);
            http_response_code(500);
            die('Database connection failed.');
        }
        $conn->set_charset('utf8mb4');
    }
    return $conn;
}

// Helper function to safely redirect
function redirect($url) {
    header("Location: $url");
    exit;
}

// Helper function to check if recipient is logged in
function requireRecipientLogin() {
    if (empty($_SESSION['recipient_id'])) {
        redirect('recipient-login.php');
    }
}

// Helper function to check if donor is logged in
function requireDonorLogin() {
    if (empty($_SESSION['donor_id'])) {
        redirect('donor-login.php');
    }
}

// Helper function to check if admin is logged in
function requireAdminLogin() {
    if (empty($_SESSION['admin_id'])) {
        redirect('adminportal.php');
    }
}
