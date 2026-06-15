<?php
session_start();
require_once '../db.php';
requireDonorLogin();

$donorId = $_SESSION['donor_id'];

// Fetch donor details
$stmt = $pdo->prepare("SELECT * FROM donors WHERE id = ?");
$stmt->execute([$donorId]);
$donor = $stmt->fetch();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone'] ?? '');
    $district = trim($_POST['district'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    try {
        // Update profile info
        if (!empty($phone) && !empty($district)) {
            $stmt = $pdo->prepare("UPDATE donors SET phone = ?, district = ?, address = ? WHERE id = ?");
            $stmt->execute([$phone, $district, $address, $donorId]);
            $_SESSION['donor_district'] = $district;
            $message = 'Profile updated successfully.';
        }

        // Update password
        if (!empty($current_password) || !empty($new_password)) {
            if (!password_verify($current_password, $donor['password'])) {
                $error = 'Current password is incorrect.';
            } elseif (strlen($new_password) < 6) {
                $error = 'New password must be at least 6 characters.';
            } elseif ($new_password !== $confirm_password) {
                $error = 'New passwords do not match.';
            } else {
                $stmt = $pdo->prepare("UPDATE donors SET password = ? WHERE id = ?");
                $stmt->execute([password_hash($new_password, PASSWORD_DEFAULT), $donorId]);
                $message = 'Password updated successfully.';
            }
        }

        // Refresh donor data
        $stmt = $pdo->prepare("SELECT * FROM donors WHERE id = ?");
        $stmt->execute([$donorId]);
        $donor = $stmt->fetch();

    } catch (PDOException $e) {
        $error = 'Update failed: ' . $e->getMessage();
    }
}

// Pending requests count for badge
$stmt = $pdo->prepare("SELECT COUNT(*) FROM blood_requests WHERE status = 'pending' AND blood_group = ? AND district = ?");
$stmt->execute([$donor['blood_group'], $donor['district']]);
$pendingRequestsCount = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Blood Connect – Donor Settings</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="donorDashbord.css" rel="stylesheet"/>
  <style>
    .settings-form { max-width: 700px; }
    .form-group { margin-bottom: 18px; }
    .form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--text-mid); margin-bottom: 7px; }
    .form-group input, .form-group select {
      width: 100%; padding: 12px 16px; border: 1.5px solid var(--border);
      border-radius: 10px; background: #FAFBFC; font-family: var(--font); font-size: 14px;
    }
    .form-group input:focus, .form-group select:focus {
      border-color: var(--red-primary); box-shadow: 0 0 0 3px rgba(192,21,42,0.09); background: white; outline: none;
    }
    .btn-save {
      background: var(--red-primary); color: white; border: none; border-radius: 9px;
      padding: 12px 24px; font-weight: 600; cursor: pointer; font-family: var(--font);
    }
    .btn-save:hover { background: var(--red-dark); }
    .message { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
    .message.success { background: #e6f4ea; color: #1e7e34; }
    .message.error { background: var(--red-light); color: var(--red-dark); }
    .section-title { font-size: 15px; font-weight: 700; margin: 24px 0 12px; color: var(--text-dark); }
  </style>
</head>
<body>

  <aside class="sidebar">
    <div class="sidebar-logo">
      <span class="logo-icon"><i class="fa-solid fa-droplet"></i></span>
      <div>
        <div class="logo-title">Blood Connect</div>
        <div class="logo-sub">Donor Portal</div>
      </div>
    </div>

    <nav class="sidebar-nav">
      <a href="donorDashboard.php" class="nav-item">
        <i class="fa-solid fa-grid-2"></i> Dashboard
      </a>
      <a href="donorProfile.php" class="nav-item">
        <i class="fa-regular fa-user"></i> My Profile
      </a>
      <a href="donorRequest.php" class="nav-item">
        <i class="fa-regular fa-clock"></i> Donation History
      </a>
      <a href="donorRequests.php" class="nav-item">
        <i class="fa-regular fa-envelope"></i> Requests
        <span class="badge"><?php echo (int)$pendingRequestsCount; ?></span>
      </a>
      <a href="donorSettings.php" class="nav-item active">
        <i class="fa-regular fa-gear"></i> Settings
      </a>
    </nav>

    <a href="logout.php" class="nav-item signout">
      <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
    </a>
  </aside>

  <main class="main">
    <header class="topbar">
      <div>
        <h1 class="page-title">Settings</h1>
        <p class="page-sub">Update your contact details and password.</p>
      </div>
      <div class="topbar-right">
        <div class="user-chip">
          <div class="user-info">
            <span class="user-name"><?php echo htmlspecialchars($donor['name']); ?></span>
            <span class="user-role">Regular Donor</span>
          </div>
          <div class="avatar"><i class="fa-solid fa-user"></i></div>
        </div>
      </div>
    </header>

    <?php if ($message): ?>
      <div class="message success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="mid-row" style="grid-template-columns: 1fr;">
      <form class="card settings-form" method="POST" action="">
        <div class="requests-header">
          <div>
            <div class="card-title">Profile Settings</div>
            <div class="card-sub">Keep your contact information up to date.</div>
          </div>
        </div>

        <div class="form-group">
          <label>Phone Number</label>
          <input type="text" name="phone" value="<?php echo htmlspecialchars($donor['phone']); ?>" required>
        </div>

        <div class="form-group">
          <label>District</label>
          <select name="district" required>
            <option value="">Select district</option>
            <option value="Dhaka" <?php if($donor['district'] == 'Dhaka') echo 'selected'; ?>>Dhaka</option>
            <option value="Chittagong" <?php if($donor['district'] == 'Chittagong') echo 'selected'; ?>>Chittagong</option>
            <option value="Rajshahi" <?php if($donor['district'] == 'Rajshahi') echo 'selected'; ?>>Rajshahi</option>
            <option value="Khulna" <?php if($donor['district'] == 'Khulna') echo 'selected'; ?>>Khulna</option>
            <option value="Barisal" <?php if($donor['district'] == 'Barisal') echo 'selected'; ?>>Barisal</option>
            <option value="Sylhet" <?php if($donor['district'] == 'Sylhet') echo 'selected'; ?>>Sylhet</option>
            <option value="Rangpur" <?php if($donor['district'] == 'Rangpur') echo 'selected'; ?>>Rangpur</option>
            <option value="Mymensingh" <?php if($donor['district'] == 'Mymensingh') echo 'selected'; ?>>Mymensingh</option>
          </select>
        </div>

        <div class="form-group">
          <label>Address</label>
          <input type="text" name="address" value="<?php echo htmlspecialchars($donor['address']); ?>" placeholder="Area / Thana / Street">
        </div>

        <div class="section-title">Change Password</div>

        <div class="form-group">
          <label>Current Password</label>
          <input type="password" name="current_password" placeholder="Enter current password">
        </div>

        <div class="form-group">
          <label>New Password</label>
          <input type="password" name="new_password" placeholder="At least 6 characters">
        </div>

        <div class="form-group">
          <label>Confirm New Password</label>
          <input type="password" name="confirm_password" placeholder="Re-enter new password">
        </div>

        <button type="submit" class="btn-save">Save Changes</button>
      </form>
    </div>
  </main>

  <footer class="site-footer">
    <div class="footer-left">
      <div class="footer-logo">
        <i class="fa-solid fa-droplet"></i> Blood connect
      </div>
      <div class="footer-copy">© <?php echo date('Y'); ?> Blood connect. The Pulse of Precision</div>
    </div>
    <div class="footer-links">
      <a href="#">Privacy Policy</a>
      <a href="#">Terms of Service</a>
      <a href="#">Contact Support</a>
    </div>
  </footer>

</body>
</html>
