<?php
session_start();
require_once '../db.php';
requireDonorLogin();

$donorId = $_SESSION['donor_id'];
$donorName = $_SESSION['donor_name'];

// Fetch donation stats
$stmt = $pdo->prepare("SELECT COUNT(*) FROM donations WHERE donor_id = ? AND status = 'completed'");
$stmt->execute([$donorId]);
$totalDonations = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COALESCE(SUM(units), 0) FROM donations WHERE donor_id = ? AND status = 'completed'");
$stmt->execute([$donorId]);
$totalVolume = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT donation_date FROM donations WHERE donor_id = ? AND status = 'completed' ORDER BY donation_date DESC LIMIT 1");
$stmt->execute([$donorId]);
$lastDonation = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(DISTINCT YEAR(donation_date)) FROM donations WHERE donor_id = ? AND status = 'completed'");
$stmt->execute([$donorId]);
$activeYears = $stmt->fetchColumn();

// Fetch donation history
$stmt = $pdo->prepare("SELECT * FROM donations WHERE donor_id = ? ORDER BY donation_date DESC");
$stmt->execute([$donorId]);
$donations = $stmt->fetchAll();

// Pending requests count for badge
$stmt = $pdo->prepare("SELECT COUNT(*) FROM blood_requests WHERE status = 'pending' AND blood_group = ? AND district = ?");
$stmt->execute([$_SESSION['donor_blood_group'], $_SESSION['donor_district']]);
$pendingRequestsCount = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Blood Connect – Donation History</title>
  <link rel="stylesheet" href="donorRequest.css" />
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body>

  
  <aside class="sidebar">
    <div class="sidebar-logo">
      <div class="logo-icon">
        <i data-lucide="droplets"></i>
      </div>
      <div class="logo-text">
        <span class="logo-title">Blood Connect</span>
        <span class="logo-sub">Donor Portal</span>
      </div>
    </div>

    <nav class="sidebar-nav">
      <a href="donorDashboard.php" class="nav-item">
        <i data-lucide="layout-dashboard"></i>
        <span>Dashboard</span>
      </a>
      <a href="donorProfile.php" class="nav-item">
        <i data-lucide="user"></i>
        <span>My Profile</span>
      </a>
      <a href="donorRequest.php" class="nav-item active">
        <i data-lucide="heart-pulse"></i>
        <span>Donation History</span>
      </a>
      <a href="donorRequests.php" class="nav-item">
        <i data-lucide="inbox"></i>
        <span>Requests</span>
        <span class="badge"><?php echo (int)$pendingRequestsCount; ?></span>
      </a>
      <a href="donorSettings.php" class="nav-item">
        <i data-lucide="settings"></i>
        <span>Settings</span>
      </a>
    </nav>

    <a href="logout.php" class="nav-item signout">
      <i data-lucide="log-out"></i>
      <span>Sign Out</span>
    </a>
  </aside>

  
  <main class="main">

    
    <section class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon icon-red">
          <i data-lucide="heart"></i>
        </div>
        <div class="stat-body">
          <span class="stat-label">Total Donations</span>
          <span class="stat-value"><?php echo (int)$totalDonations; ?></span>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon icon-blue">
          <i data-lucide="droplet"></i>
        </div>
        <div class="stat-body">
          <span class="stat-label">Total Volume</span>
          <span class="stat-value"><?php echo number_format((int)$totalVolume); ?> <small>mL</small></span>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon icon-purple">
          <i data-lucide="calendar-check"></i>
        </div>
        <div class="stat-body">
          <span class="stat-label">Last Donation</span>
          <span class="stat-value"><?php echo $lastDonation ? date('M d,<br/>Y', strtotime($lastDonation)) : '—'; ?></span>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon icon-orange">
          <i data-lucide="flame"></i>
        </div>
        <div class="stat-body">
          <span class="stat-label">Active Years</span>
          <span class="stat-value"><?php echo (int)$activeYears; ?></span>
        </div>
      </div>
    </section>

    
    <section class="table-section">
      <div class="table-header">
        <h2 class="table-title">Donation History</h2>
        <div class="table-controls">
          <div class="search-box">
            <i data-lucide="search"></i>
            <input type="text" placeholder="Search ID…" />
          </div>
          <button class="filter-btn">
            <i data-lucide="sliders-horizontal"></i>
            Filters
          </button>
        </div>
      </div>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Donation ID</th>
              <th>Date</th>
              <th>Location</th>
              <th>Blood Group</th>
              <th>Units</th>
              <th>Status</th>
              <th>Certificate</th>
            </tr>
          </thead>
          <tbody>
            <?php if (count($donations) > 0): ?>
              <?php foreach ($donations as $index => $donation): ?>
                <tr>
                  <td class="id-cell">#DN-<?php echo str_pad($donation['id'], 3, '0', STR_PAD_LEFT); ?></td>
                  <td><?php echo date('M d, Y', strtotime($donation['donation_date'])); ?></td>
                  <td><?php echo htmlspecialchars($donation['location']); ?></td>
                  <td><span class="blood-badge red"><?php echo htmlspecialchars($donation['blood_group']); ?></span></td>
                  <td><?php echo (int)$donation['units']; ?> mL</td>
                  <td><span class="status <?php echo $donation['status'] == 'completed' ? 'completed' : 'cancelled'; ?>"><?php echo ucfirst(htmlspecialchars($donation['status'])); ?></span></td>
                  <td>
                    <?php if ($donation['status'] == 'completed'): ?>
                      <button class="cert-btn" title="Download Certificate"><i data-lucide="download"></i></button>
                    <?php else: ?>
                      —
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="7" style="text-align:center; padding: 24px; color: var(--text-soft);">No donation history found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      
      <div class="pagination-bar">
        <span class="page-info">Showing <?php echo count($donations); ?> entr<?php echo count($donations) === 1 ? 'y' : 'ies'; ?></span>
        <div class="pagination">
          <button class="page-btn" disabled><i data-lucide="chevron-left"></i></button>
          <button class="page-btn active">1</button>
          <button class="page-btn" disabled><i data-lucide="chevron-right"></i></button>
        </div>
      </div>
    </section>

  </main>

  
  <footer class="site-footer">
    <div class="footer-brand">
      <div class="footer-logo">
        <i data-lucide="droplets"></i>
        <span>Blood Connect</span>
      </div>
      <p class="footer-copy">© <?php echo date('Y'); ?> Blood Connect. The Pulse of Precision</p>
    </div>
    <div class="footer-links">
      <a href="#">Privacy Policy</a>
      <a href="#">Terms of Service</a>
      <a href="#">Contact Support</a>
    </div>
  </footer>

  <script>
    lucide.createIcons();
  </script>
</body>
</html>
