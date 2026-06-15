<?php
// admindashboard.php - Blood Connect Admin Dashboard
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | Blood Connect</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="admindashboard.css">
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
        <a href="admindashboard.php" class="nav-item active">
          <i class="fa-solid fa-grip"></i> Dashboard
        </a>
        <a href="donor-verification.php" class="nav-item">
          <i class="fa-solid fa-user-check"></i> Donor Verification
        </a>
        <a href="emergency-requests.php" class="nav-item">
          <i class="fa-solid fa-clipboard-list"></i> Emergency Requests
        </a>
      </nav>

      <a href="logout.php" class="sign-out">
        <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
      </a>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <h1>Admin Dashboard</h1>
      <p class="page-subtitle">Overview of platform activity and pending actions</p>

      <!-- Stat Cards -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon icon-orange">
            <i class="fa-regular fa-clock"></i>
          </div>
          <h2>4</h2>
          <p>Pending Verifications</p>
        </div>

        <div class="stat-card">
          <div class="stat-icon icon-green">
            <i class="fa-solid fa-circle-check"></i>
          </div>
          <h2>1</h2>
          <p>Verified Accounts</p>
        </div>

        <div class="stat-card">
          <div class="stat-icon icon-red">
            <i class="fa-solid fa-circle-xmark"></i>
          </div>
          <h2>1</h2>
          <p>Rejected Accounts</p>
        </div>

        <div class="stat-card">
          <div class="stat-icon icon-yellow">
            <i class="fa-solid fa-file-lines"></i>
          </div>
          <h2>2</h2>
          <p>Pending Requests</p>
        </div>
      </div>

      <!-- Action Cards -->
      <div class="action-grid">
        <div class="action-card">
          <div class="action-icon icon-red-soft">
            <i class="fa-solid fa-users"></i>
          </div>
          <h3>Donor Verification</h3>
          <p>Review and verify blood donor accounts to ensure platform integrity</p>
          <a href="donor-verification.php" class="action-link">Manage donors <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="action-card">
          <div class="action-icon icon-red-soft">
            <i class="fa-solid fa-clipboard-list"></i>
          </div>
          <h3>Emergency Requests</h3>
          <p>Monitor and approve emergency blood donation requests</p>
          <a href="emergency-requests.php" class="action-link">Manage requests <i class="fa-solid fa-arrow-right"></i></a>
        </div>
      </div>
    </main>

  </div>

</body>
</html>