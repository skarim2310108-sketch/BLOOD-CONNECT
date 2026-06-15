<?php
// setup.php - One-time database installer for Blood Connect
// Run this once by visiting: http://localhost/BLOOD-CONNECT/front/setup.php

$host = 'localhost';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS bloodconnect CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE bloodconnect");

    // Recipients table
    $pdo->exec("CREATE TABLE IF NOT EXISTS recipients (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        phone VARCHAR(20) NOT NULL,
        blood_group VARCHAR(5) NOT NULL,
        district VARCHAR(50) NOT NULL,
        address TEXT,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Donors table
    $pdo->exec("CREATE TABLE IF NOT EXISTS donors (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        phone VARCHAR(20) NOT NULL,
        blood_group VARCHAR(5) NOT NULL,
        district VARCHAR(50) NOT NULL,
        address TEXT,
        status ENUM('available','unavailable') DEFAULT 'available',
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Blood requests table
    $pdo->exec("CREATE TABLE IF NOT EXISTS blood_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        recipient_id INT NOT NULL,
        patient_name VARCHAR(100) NOT NULL,
        age INT NOT NULL,
        blood_group VARCHAR(5) NOT NULL,
        units INT NOT NULL,
        hospital VARCHAR(100) NOT NULL,
        district VARCHAR(50) NOT NULL,
        address TEXT,
        status ENUM('pending','fulfilled','cancelled') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (recipient_id) REFERENCES recipients(id) ON DELETE CASCADE
    )");

    // Insert sample donors if none exist
    $count = $pdo->query("SELECT COUNT(*) FROM donors")->fetchColumn();
    if ($count == 0) {
        $sampleDonors = [
            ['Rahim Uddin', 'rahim@example.com', '01711111111', 'B+', 'Dhaka', 'Mirpur, Dhaka', password_hash('123456', PASSWORD_DEFAULT)],
            ['Fatema Begum', 'fatema@example.com', '01822222222', 'A-', 'Dhaka', 'Dhanmondi, Dhaka', password_hash('123456', PASSWORD_DEFAULT)],
            ['Kamal Hossain', 'kamal@example.com', '01933333333', 'O+', 'Dhaka', 'Uttara, Dhaka', password_hash('123456', PASSWORD_DEFAULT)],
            ['Nusrat Jahan', 'nusrat@example.com', '01644444444', 'AB+', 'Chittagong', 'Agrabad, Chittagong', password_hash('123456', PASSWORD_DEFAULT)],
            ['Sabbir Rahman', 'sabbir@example.com', '01555555555', 'A+', 'Rajshahi', 'Shaheb Bazar, Rajshahi', password_hash('123456', PASSWORD_DEFAULT)],
        ];

        $stmt = $pdo->prepare("INSERT INTO donors (name, email, phone, blood_group, district, address, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($sampleDonors as $donor) {
            $stmt->execute($donor);
        }
    }

    echo "<h2>Setup Complete</h2>";
    echo "<p>Database <strong>bloodconnect</strong> and tables created successfully.</p>";
    echo "<p>Sample donors inserted.</p>";
    echo "<a href='recipient/register.php'>Go to Recipient Registration</a>";

} catch (PDOException $e) {
    die("Setup failed: " . $e->getMessage());
}
