<?php
session_start();
require_once '../db.php';
requireDonorLogin();

$donorId = $_SESSION['donor_id'];

// Fetch donor details
$stmt = $pdo->prepare("SELECT * FROM donors WHERE id = ?");
$stmt->execute([$donorId]);
$donor = $stmt->fetch();

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
  <title>Blood Connect – Donor Profile</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="donorDashbord.css" rel="stylesheet"/>
  <style>
    .profile-card { max-width: 700px; }
    .profile-row { display: flex; justify-content: space-between; padding: 16px 0; border-bottom: 1px solid var(--border); }
    .profile-row:last-child { border-bottom: none; }
    .profile-label { color: var(--text-soft); font-size: 13px; font-weight: 500; }
    .profile-value { color: var(--text-dark); font-size: 14px; font-weight: 600; }
    .blood-badge-inline {
      display: inline-flex; align-items: center; justify-content: center;
      background: var(--red-primary); color: white;
      padding: 4px 12px; border-radius: 20px; font-weight: 700;
    }
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
      <a href="donorProfile.php" class="nav-item active">
        <i class="fa-regular fa-user"></i> My Profile
      </a>
      <a href="donorRequest.php" class="nav-item">
        <i class="fa-regular fa-clock"></i> Donation History
      </a>
      <a href="donorRequests.php" class="nav-item">
        <i class="fa-regular fa-envelope"></i> Requests
        <span class="badge"><?php echo (int)$pendingRequestsCount; ?></span>
      </a>
      <a href="donorSettings.php" class="nav-item">
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
        <h1 class="page-title">My Profile</h1>
        <p class="page-sub">Your personal and contact information.</p>
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

    <div class="mid-row" style="grid-template-columns: 1fr;">
      <div class="card profile-card">
        <div class="requests-header">
          <div>
            <div class="card-title">Profile Information</div>
            <div class="card-sub">Details you provided during registration.</div>
          </div>
        </div>

        <div class="profile-row">
          <span class="profile-label">Full Name</span>
          <span class="profile-value"><?php echo htmlspecialchars($donor['name']); ?></span>
        </div>
        <div class="profile-row">
          <span class="profile-label">Email Address</span>
          <span class="profile-value"><?php echo htmlspecialchars($donor['email']); ?></span>
        </div>
        <div class="profile-row">
          <span class="profile-label">Phone Number</span>
          <span class="profile-value"><?php echo htmlspecialchars($donor['phone']); ?></span>
        </div>
        <div class="profile-row">
          <span class="profile-label">Blood Group</span>
          <span class="profile-value"><span class="blood-badge-inline"><?php echo htmlspecialchars($donor['blood_group']); ?></span></span>
        </div>
        <div class="profile-row">
          <span class="profile-label">District</span>
          <span class="profile-value"><?php echo htmlspecialchars($donor['district']); ?></span>
        </div>
        <div class="profile-row">
          <span class="profile-label">Address</span>
          <span class="profile-value"><?php echo htmlspecialchars($donor['address'] ?: '—'); ?></span>
        </div>
        <div class="profile-row">
          <span class="profile-label">Account Status</span>
          <span class="profile-value" style="color: #16A34A; text-transform: capitalize;"><?php echo htmlspecialchars($donor['status']); ?></span>
        </div>
        <div class="profile-row">
          <span class="profile-label">Member Since</span>
          <span class="profile-value"><?php echo date('M d, Y', strtotime($donor['created_at'])); ?></span>
        </div>
      </div>
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
