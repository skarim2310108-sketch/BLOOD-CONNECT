<?php
// admindashboard.php - Blood Connect Admin Dashboard
session_start();

// ─── DB Connection ───────────────────────────────────────────────
$host = "localhost";
$dbname = "bloodconnect";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// ─── Admin Auth Check ─────────────────────────────────────────────
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminportal.php");
    exit();
}

$admin_name = $_SESSION['admin_name'] ?? 'Admin';

// ─── Fetch Stats from DB ──────────────────────────────────────────

// Pending donor verifications
$pending_verifications = $conn->query(
    "SELECT COUNT(*) AS total FROM donors WHERE status = 'pending'"
)->fetch_assoc()['total'];

// Verified donor accounts
$verified_accounts = $conn->query(
    "SELECT COUNT(*) AS total FROM donors WHERE status = 'verified'"
)->fetch_assoc()['total'];

// Rejected donor accounts
$rejected_accounts = $conn->query(
    "SELECT COUNT(*) AS total FROM donors WHERE status = 'rejected'"
)->fetch_assoc()['total'];

// Pending emergency blood requests
$pending_requests = $conn->query(
    "SELECT COUNT(*) AS total FROM emergency_requests WHERE status = 'pending'"
)->fetch_assoc()['total'];

// Total donors
$total_donors = $conn->query(
    "SELECT COUNT(*) AS total FROM donors"
)->fetch_assoc()['total'];

// Total emergency requests
$total_requests = $conn->query(
    "SELECT COUNT(*) AS total FROM emergency_requests"
)->fetch_assoc()['total'];

// ─── Recent Activity (last 5 donors) ─────────────────────────────
$recent_donors = $conn->query(
    "SELECT full_name, blood_type, status, created_at 
     FROM donors 
     ORDER BY created_at DESC 
     LIMIT 5"
);

$conn->close();
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

      <div class="page-header">
        <div>
          <h1>Admin Dashboard</h1>
          <p class="page-subtitle">Welcome back, <?php echo htmlspecialchars($admin_name); ?>! Here's your platform overview.</p>
        </div>
        <div class="current-date">
          <i class="fa-regular fa-calendar"></i>
          <?php echo date('D, d M Y'); ?>
        </div>
      </div>

      <!-- Stat Cards — Dynamic from DB -->
      <div class="stats-grid">

        <div class="stat-card">
          <div class="stat-icon icon-orange">
            <i class="fa-regular fa-clock"></i>
          </div>
          <h2><?php echo $pending_verifications; ?></h2>
          <p>Pending Verifications</p>
        </div>

        <div class="stat-card">
          <div class="stat-icon icon-green">
            <i class="fa-solid fa-circle-check"></i>
          </div>
          <h2><?php echo $verified_accounts; ?></h2>
          <p>Verified Accounts</p>
        </div>

        <div class="stat-card">
          <div class="stat-icon icon-red">
            <i class="fa-solid fa-circle-xmark"></i>
          </div>
          <h2><?php echo $rejected_accounts; ?></h2>
          <p>Rejected Accounts</p>
        </div>

        <div class="stat-card">
          <div class="stat-icon icon-yellow">
            <i class="fa-solid fa-file-lines"></i>
          </div>
          <h2><?php echo $pending_requests; ?></h2>
          <p>Pending Requests</p>
        </div>

        <div class="stat-card">
          <div class="stat-icon icon-blue">
            <i class="fa-solid fa-users"></i>
          </div>
          <h2><?php echo $total_donors; ?></h2>
          <p>Total Donors</p>
        </div>

        <div class="stat-card">
          <div class="stat-icon icon-purple">
            <i class="fa-solid fa-heart-pulse"></i>
          </div>
          <h2><?php echo $total_requests; ?></h2>
          <p>Total Requests</p>
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
          <?php if ($pending_verifications > 0): ?>
            <span class="badge badge-orange"><?php echo $pending_verifications; ?> pending</span>
          <?php endif; ?>
          <a href="donor-verification.php" class="action-link">Manage donors <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="action-card">
          <div class="action-icon icon-red-soft">
            <i class="fa-solid fa-clipboard-list"></i>
          </div>
          <h3>Emergency Requests</h3>
          <p>Monitor and approve emergency blood donation requests</p>
          <?php if ($pending_requests > 0): ?>
            <span class="badge badge-red"><?php echo $pending_requests; ?> pending</span>
          <?php endif; ?>
          <a href="emergency-requests.php" class="action-link">Manage requests <i class="fa-solid fa-arrow-right"></i></a>
        </div>
      </div>

      <!-- Recent Donor Activity Table -->
      <div class="recent-section">
        <h2>Recent Donor Registrations</h2>
        <table class="data-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Blood Type</th>
              <th>Status</th>
              <th>Registered At</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($recent_donors && $recent_donors->num_rows > 0): ?>
              <?php while ($row = $recent_donors->fetch_assoc()): ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                  <td><span class="blood-badge"><?php echo htmlspecialchars($row['blood_type']); ?></span></td>
                  <td>
                    <?php
                      $status = $row['status'];
                      $class = match($status) {
                        'verified'  => 'status-green',
                        'rejected'  => 'status-red',
                        default     => 'status-orange'
                      };
                    ?>
                    <span class="status-pill <?php echo $class; ?>">
                      <?php echo ucfirst($status); ?>
                    </span>
                  </td>
                  <td><?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" class="no-data">No donor registrations yet.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </main>
  </div>

</body>
</html>
