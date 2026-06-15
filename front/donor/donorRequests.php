<?php
session_start();
require_once '../db.php';
requireDonorLogin();

$donorId = $_SESSION['donor_id'];
$donorName = $_SESSION['donor_name'];
$donorBloodGroup = $_SESSION['donor_blood_group'];
$donorDistrict = $_SESSION['donor_district'];

// Fetch donor details for eligibility
$stmt = $pdo->prepare("SELECT * FROM donors WHERE id = ?");
$stmt->execute([$donorId]);
$donor = $stmt->fetch();

// Calculate eligibility
$stmt = $pdo->prepare("SELECT donation_date FROM donations WHERE donor_id = ? AND status = 'completed' ORDER BY donation_date DESC LIMIT 1");
$stmt->execute([$donorId]);
$lastDonation = $stmt->fetchColumn();

$eligible = true;
if ($lastDonation) {
    $lastDate = new DateTime($lastDonation);
    $nextEligible = clone $lastDate;
    $nextEligible->modify('+90 days');
    $today = new DateTime();
    if ($today < $nextEligible) {
        $eligible = false;
    }
}

// Fetch pending requests
$stmt = $pdo->prepare("SELECT * FROM blood_requests 
    WHERE status = 'pending' 
    AND blood_group = ? 
    AND district = ? 
    ORDER BY created_at DESC");
$stmt->execute([$donorBloodGroup, $donorDistrict]);
$pendingRequests = $stmt->fetchAll();

// Handle accept/decline
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'])) {
    $requestId = (int)$_POST['request_id'];
    $action = $_POST['action'] ?? '';

    if ($action === 'accept') {
        $stmt = $pdo->prepare("SELECT * FROM blood_requests WHERE id = ? AND status = 'pending'");
        $stmt->execute([$requestId]);
        $request = $stmt->fetch();

        if ($request && $eligible) {
            try {
                $pdo->beginTransaction();
                $stmt = $pdo->prepare("INSERT INTO donations (donor_id, request_id, donation_date, location, blood_group, units, status) VALUES (?, ?, CURDATE(), ?, ?, ?, 'completed')");
                $stmt->execute([$donorId, $requestId, $request['hospital'], $request['blood_group'], $request['units']]);
                $stmt = $pdo->prepare("UPDATE blood_requests SET status = 'fulfilled' WHERE id = ?");
                $stmt->execute([$requestId]);
                $pdo->commit();
                header("Location: donorRequests.php?msg=" . urlencode('Request accepted. Thank you for donating!'));
                exit;
            } catch (PDOException $e) {
                $pdo->rollBack();
                $message = 'Failed to accept request: ' . $e->getMessage();
            }
        } else {
            $message = 'You are not eligible to donate yet. Cooling period active.';
        }
    }
}

if (isset($_GET['msg'])) {
    $message = $_GET['msg'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Blood Connect – Donor Requests</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="donorDashbord.css" rel="stylesheet"/>
  <style>
    .donor-message {
      background: #e6f4ea; color: #1e7e34;
      padding: 12px 16px; border-radius: 8px;
      margin-bottom: 16px; font-size: 14px;
    }
    .donor-message.error { background: var(--red-light); color: var(--red-dark); }
    .requests-list { display: flex; flex-direction: column; gap: 12px; }
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
      <a href="donorRequests.php" class="nav-item active">
        <i class="fa-regular fa-envelope"></i> Requests
        <span class="badge"><?php echo count($pendingRequests); ?></span>
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
        <h1 class="page-title">Blood Requests</h1>
        <p class="page-sub">Patients in your area matching your blood group (<?php echo htmlspecialchars($donorBloodGroup); ?>).</p>
      </div>
      <div class="topbar-right">
        <div class="user-chip">
          <div class="user-info">
            <span class="user-name"><?php echo htmlspecialchars($donorName); ?></span>
            <span class="user-role">Regular Donor</span>
          </div>
          <div class="avatar"><i class="fa-solid fa-user"></i></div>
        </div>
      </div>
    </header>

    <?php if ($message): ?>
      <div class="donor-message <?php echo strpos($message, 'Failed') !== false || strpos($message, 'not eligible') !== false ? 'error' : ''; ?>"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <div class="mid-row" style="grid-template-columns: 1fr;">
      <div class="card requests-card">
        <div class="requests-header">
          <div>
            <div class="card-title">Pending Requests</div>
            <div class="card-sub">All urgent blood requests matching your profile.</div>
          </div>
        </div>

        <div class="requests-list">
          <?php if (count($pendingRequests) > 0): ?>
            <?php foreach ($pendingRequests as $req): ?>
              <div class="request-item">
                <div class="req-blood-badge bplus"><?php echo htmlspecialchars($req['blood_group']); ?></div>
                <div class="req-info">
                  <div class="req-name-row">
                    <span class="req-name"><?php echo htmlspecialchars($req['patient_name']); ?></span>
                    <span class="req-urgency critical">Critical</span>
                  </div>
                  <div class="req-meta">
                    <span><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($req['hospital']); ?></span>
                    <span class="req-dist">(<?php echo htmlspecialchars($req['district']); ?>)</span>
                    <span><i class="fa-regular fa-clock"></i> <?php echo date('M d, Y', strtotime($req['created_at'])); ?></span>
                    <span><i class="fa-solid fa-droplet"></i> <?php echo (int)$req['units']; ?> unit(s)</span>
                  </div>
                </div>
                <div class="req-actions">
                  <form method="POST" action="" style="display:flex;gap:8px;">
                    <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                    <button type="submit" name="action" value="decline" class="req-decline">Decline</button>
                    <button type="submit" name="action" value="accept" class="req-accept <?php echo !$eligible ? 'disabled' : ''; ?>" <?php echo !$eligible ? 'disabled' : ''; ?>>Accept Request</button>
                  </form>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p style="padding: 24px; color: var(--text-soft); text-align: center;">No pending requests matching your blood group in your district.</p>
          <?php endif; ?>
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
