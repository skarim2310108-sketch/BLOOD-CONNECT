<?php
// db.php - Shared database connection for Blood Connect
// Default XAMPP credentials: host=localhost, user=root, password=(empty), db=bloodconnect

$host = 'localhost';
$dbname = 'bloodconnect';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed. Please make sure XAMPP MySQL is running and the database exists.<br>Error: " . $e->getMessage());
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
