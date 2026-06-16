<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_URL', '/BLOOD-CONNECT');

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

function redirect($url) {
    header("Location: $url");
    exit;
}

function requireRecipientLogin() {
    if (empty($_SESSION['recipient_id'])) {
        redirect(BASE_URL . '/recipient/recipient-login.php');
    }
}

function requireDonorLogin() {
    if (empty($_SESSION['donor_id'])) {
        redirect(BASE_URL . '/donor/donor-login.php');
    }
}

function requireAdminLogin() {
    if (empty($_SESSION['admin_id'])) {
        redirect(BASE_URL . '/Admin/adminportal.php');
    }
}