<?php
// donorvarification.php - Blood Connect Donor Verification Portal
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Donor Verification Portal | Blood Connect</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="donorvarification.css">
</head>
<body>

  <div class="layout">

    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-brand">
        <div class="logo">
          <i class="fa-solid fa-droplet"></i>
          <span>Blood Connect</span>
        </div>
        <p class="sidebar-subtitle">Admin Portal</p>
      </div>

      <nav class="sidebar-nav">
        <a href="admindashboard.php" class="nav-item">
          <i class="fa-solid fa-grip"></i> Dashboard
        </a>
        <a href="donorvarification.php" class="nav-item active">
          <i class="fa-solid fa-user-check"></i> Donor Verification
        </a>
        <a href="emergencyRequest.php" class="nav-item">
          <i class="fa-solid fa-clipboard-list"></i> Emergency Requests
        </a>
      </nav>

      <a href="logout.php" class="sign-out">
        <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
      </a>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <h1>Donor Verification Portal</h1>
      <p class="page-subtitle">Review and verify blood donor accounts to ensure platform integrity</p>

      <!-- Stat Cards -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-text">
            <h2>4</h2>
            <p>Pending Verifications</p>
          </div>
          <div class="stat-icon icon-orange">
            <i class="fa-regular fa-clock"></i>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-text">
            <h2>0</h2>
            <p>Verified Accounts</p>
          </div>
          <div class="stat-icon icon-green">
            <i class="fa-solid fa-circle-check"></i>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-text">
            <h2>0</h2>
            <p>Rejected Accounts</p>
          </div>
          <div class="stat-icon icon-red">
            <i class="fa-solid fa-circle-xmark"></i>
          </div>
        </div>
      </div>

      <!-- Filter Tabs -->
      <div class="filter-tabs">
        <button class="tab active">All (4)</button>
        <button class="tab">Pending (4)</button>
        <button class="tab">Verified (0)</button>
        <button class="tab">Rejected (0)</button>
      </div>

      <!-- Donor List -->
      <div class="donor-list">

        <div class="donor-card">
          <div class="blood-badge badge-b-pos">B+</div>
          <div class="donor-info">
            <div class="donor-name-row">
              <h3>Muzfiqur</h3>
              <span class="badge badge-pending">PENDING</span>
            </div>
            <div class="donor-details">
              <div class="detail">
                <span class="detail-label">Donor ID</span>
                <span class="detail-value">D-12345</span>
              </div>
              <div class="detail">
                <span class="detail-label">Age</span>
                <span class="detail-value">22</span>
              </div>
              <div class="detail">
                <span class="detail-label">Registered</span>
                <span class="detail-value">2/15/2026</span>
              </div>
              <div class="detail">
                <span class="detail-label">Expired</span>
                <span class="detail-value text-danger">3/10/2025</span>
              </div>
            </div>
            <div class="donor-email">
              <i class="fa-regular fa-envelope"></i> muzfiqur@example.com
            </div>
          </div>
          <div class="donor-actions">
            <button class="btn btn-reject">Reject</button>
            <button class="btn btn-verify">Verify</button>
          </div>
        </div>

        <div class="donor-card">
          <div class="blood-badge badge-a-pos">A+</div>
          <div class="donor-info">
            <div class="donor-name-row">
              <h3>Tarek</h3>
              <span class="badge badge-pending">PENDING</span>
            </div>
            <div class="donor-details">
              <div class="detail">
                <span class="detail-label">Donor ID</span>
                <span class="detail-value">D-12434</span>
              </div>
              <div class="detail">
                <span class="detail-label">Age</span>
                <span class="detail-value">22</span>
              </div>
              <div class="detail">
                <span class="detail-label">Registered</span>
                <span class="detail-value">3/10/2025</span>
              </div>
              <div class="detail">
                <span class="detail-label">Last Donation</span>
                <span class="detail-value">3/10/2025</span>
              </div>
            </div>
            <div class="donor-email">
              <i class="fa-regular fa-envelope"></i> tarek@example.com
            </div>
          </div>
          <div class="donor-actions">
            <button class="btn btn-reject">Reject</button>
            <button class="btn btn-verify">Verify</button>
          </div>
        </div>

        <div class="donor-card">
          <div class="blood-badge badge-o-pos">O+</div>
          <div class="donor-info">
            <div class="donor-name-row">
              <h3>Sarah Ahmed</h3>
              <span class="badge badge-pending">PENDING</span>
            </div>
            <div class="donor-details">
              <div class="detail">
                <span class="detail-label">Donor ID</span>
                <span class="detail-value">D-12456</span>
              </div>
              <div class="detail">
                <span class="detail-label">Age</span>
                <span class="detail-value">28</span>
              </div>
              <div class="detail">
                <span class="detail-label">Registered</span>
                <span class="detail-value">3/20/2026</span>
              </div>
              <div class="detail">
                <span class="detail-label">Last Donation</span>
                <span class="detail-value">&mdash;</span>
              </div>
            </div>
            <div class="donor-email">
              <i class="fa-regular fa-envelope"></i> sarah@example.com
            </div>
          </div>
          <div class="donor-actions">
            <button class="btn btn-reject">Reject</button>
            <button class="btn btn-verify">Verify</button>
          </div>
        </div>

      </div>
    </main>

  </div>

</body>
</html>