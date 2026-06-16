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
        status ENUM('pending','verified','rejected','available','unavailable') DEFAULT 'pending',
        reviewed_at TIMESTAMP NULL DEFAULT NULL,
        last_donation_date DATE DEFAULT NULL,
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

    // Emergency requests table (admin-managed)
    $pdo->exec("CREATE TABLE IF NOT EXISTS emergency_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        patient_name VARCHAR(100) NOT NULL,
        blood_type VARCHAR(5) NOT NULL,
        urgency VARCHAR(20) DEFAULT 'NORMAL',
        hospital VARCHAR(100) NOT NULL,
        doctor_name VARCHAR(100) DEFAULT NULL,
        contact VARCHAR(20) NOT NULL,
        status ENUM('pending','approved','rejected') DEFAULT 'pending',
        requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Donations table (for donor donation history)
    $pdo->exec("CREATE TABLE IF NOT EXISTS donations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        donor_id INT NOT NULL,
        request_id INT DEFAULT NULL,
        donation_date DATE NOT NULL,
        location VARCHAR(100) NOT NULL,
        blood_group VARCHAR(5) NOT NULL,
        units INT NOT NULL DEFAULT 450,
        status ENUM('completed','cancelled') DEFAULT 'completed',
        certificate_id VARCHAR(50) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (donor_id) REFERENCES donors(id) ON DELETE CASCADE,
        FOREIGN KEY (request_id) REFERENCES blood_requests(id) ON DELETE SET NULL
    )");

    // Admins table
    $pdo->exec("CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Insert default admin if none exist
    $adminCount = $pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();
    if ($adminCount == 0) {
        $stmt = $pdo->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute(['System Admin', 'admin@bloodconnect.com', password_hash('admin123', PASSWORD_DEFAULT)]);
    }

    // Insert sample emergency requests if none exist
    $emergencyCount = $pdo->query("SELECT COUNT(*) FROM emergency_requests")->fetchColumn();
    if ($emergencyCount == 0) {
        $sampleRequests = [
            ['Rahim Uddin', 'A+', 'URGENT', 'Square Hospital, Dhaka', 'Dr. Karim', '01711111111', 'pending'],
            ['Fatema Begum', 'B-', 'NORMAL', 'Labaid Hospital, Dhaka', 'Dr. Rahman', '01822222222', 'pending'],
            ['Kamal Hossain', 'O+', 'URGENT', 'United Hospital, Dhaka', 'Dr. Hasan', '01933333333', 'approved'],
            ['Nusrat Jahan', 'AB+', 'NORMAL', 'Apollo Hospital, Dhaka', 'Dr. Sultana', '01644444444', 'rejected'],
        ];

        $stmt = $pdo->prepare("INSERT INTO emergency_requests (patient_name, blood_type, urgency, hospital, doctor_name, contact, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($sampleRequests as $req) {
            $stmt->execute($req);
        }
    }

    // Insert sample donors if none exist
    $count = $pdo->query("SELECT COUNT(*) FROM donors")->fetchColumn();
    if ($count == 0) {
        $sampleDonors = [
            ['Rahim Uddin', 'rahim@example.com', '01711111111', 'B+', 'Dhaka', 'Mirpur, Dhaka', null, password_hash('123456', PASSWORD_DEFAULT)],
            ['Fatema Begum', 'fatema@example.com', '01822222222', 'A-', 'Dhaka', 'Dhanmondi, Dhaka', null, password_hash('123456', PASSWORD_DEFAULT)],
            ['Kamal Hossain', 'kamal@example.com', '01933333333', 'O+', 'Dhaka', 'Uttara, Dhaka', null, password_hash('123456', PASSWORD_DEFAULT)],
            ['Nusrat Jahan', 'nusrat@example.com', '01644444444', 'AB+', 'Chittagong', 'Agrabad, Chittagong', null, password_hash('123456', PASSWORD_DEFAULT)],
            ['Sabbir Rahman', 'sabbir@example.com', '01555555555', 'A+', 'Rajshahi', 'Shaheb Bazar, Rajshahi', null, password_hash('123456', PASSWORD_DEFAULT)],
        ];

        $stmt = $pdo->prepare("INSERT INTO donors (name, email, phone, blood_group, district, address, last_donation_date, status, password) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', ?)");
        foreach ($sampleDonors as $donor) {
            $stmt->execute($donor);
        }
    }

    // Make sure donors.status supports the verification workflow values
    $pdo->exec("ALTER TABLE donors MODIFY COLUMN status ENUM('pending','verified','rejected','available','unavailable') DEFAULT 'pending'");

    // Add reviewed_at column if missing (used by admin donor verification)
    try {
        $pdo->exec("ALTER TABLE donors ADD COLUMN reviewed_at TIMESTAMP NULL DEFAULT NULL");
    } catch (PDOException $e) {
        // Column may already exist — ignore error
    }

    // Add last_donation_date column if missing (used for 90-day donation cycle check)
    try {
        $pdo->exec("ALTER TABLE donors ADD COLUMN last_donation_date DATE DEFAULT NULL");
    } catch (PDOException $e) {
        // Column may already exist — ignore error
    }

    // Normalize old donor statuses (e.g. 'available') to 'pending' for verification workflow
    $pdo->exec("UPDATE donors SET status = 'pending' WHERE status IS NULL OR status = '' OR status = 'available'");

    echo "<h2>Setup Complete</h2>";
    echo "<p>Database <strong>bloodconnect</strong> and tables created successfully.</p>";
    echo "<p>Sample donors and emergency requests inserted.</p>";
    echo "<p>Default admin: <strong>admin@bloodconnect.com</strong> / password: <strong>admin123</strong></p>";
    echo "<a href='recipient/register.php'>Go to Recipient Registration</a>";

} catch (PDOException $e) {
    die("Setup failed: " . $e->getMessage());
}
