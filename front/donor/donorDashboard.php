<?php
session_start();
require_once '../db.php';
requireDonorLogin();

$donorId = $_SESSION['donor_id'];
$donorName = $_SESSION['donor_name'];
$donorBloodGroup = $_SESSION['donor_blood_group'];
$donorDistrict = $_SESSION['donor_district'];

// Fetch fresh donor details
$stmt = $pdo->prepare("SELECT * FROM donors WHERE id = ?");
$stmt->execute([$donorId]);
$donor = $stmt->fetch();

// Fetch total donations
$stmt = $pdo->prepare("SELECT COUNT(*) FROM donations WHERE donor_id = ? AND status = 'completed'");
$stmt->execute([$donorId]);
$totalDonations = $stmt->fetchColumn();

// Fetch last donation
$stmt = $pdo->prepare("SELECT donation_date FROM donations WHERE donor_id = ? AND status = 'completed' ORDER BY donation_date DESC LIMIT 1");
$stmt->execute([$donorId]);
$lastDonation = $stmt->fetchColumn();

// Calculate eligibility
$today = new DateTime();
$nextEligible = null;
$daysRemaining = 0;
$eligible = true;
$statusText = 'Available';
$statusClass = 'green-text';

if ($lastDonation) {
    $lastDate = new DateTime($lastDonation);
    $nextEligible = clone $lastDate;
    $nextEligible->modify('+90 days');
    $interval = $today->diff($nextEligible);
    $daysRemaining = (int)$today->diff($nextEligible)->format('%r%a');

    if ($daysRemaining > 0) {
        $eligible = false;
        $statusText = 'Cooling Period';
        $statusClass = 'red-text';
    }
}

$nextEligibleText = $nextEligible ? $nextEligible->format('M d, Y') : 'Now';
$lastDonationText = $lastDonation ? date('M d, Y', strtotime($lastDonation)) : 'No donations yet';

// Progress bar width
$progressWidth = 0;
if ($lastDonation) {
    if ($eligible) {
        $progressWidth = 100;
    } else {
        $lastDate = new DateTime($lastDonation);
        $totalDays = 90;
        $passedDays = (int)$lastDate->diff($today)->format('%a');
        $progressWidth = min(100, max(0, ($passedDays / $totalDays) * 100));
    }
}

// Fetch pending requests matching donor's blood group and district
$stmt = $pdo->prepare("SELECT * FROM blood_requests 
    WHERE status = 'pending' 
    AND blood_group = ? 
    AND district = ? 
    ORDER BY created_at DESC 
    LIMIT 10");
$stmt->execute([$donorBloodGroup, $donorDistrict]);
$pendingRequests = $stmt->fetchAll();

// Handle accept/decline
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'])) {
    $requestId = (int)$_POST['request_id'];
    $action = $_POST['action'] ?? '';

    if ($action === 'accept') {
        // Get request details
        $stmt = $pdo->prepare("SELECT * FROM blood_requests WHERE id = ? AND status = 'pending'");
        $stmt->execute([$requestId]);
        $request = $stmt->fetch();

        if ($request && $eligible) {
            try {
                $pdo->beginTransaction();

                // Create donation record
                $stmt = $pdo->prepare("INSERT INTO donations (donor_id, request_id, donation_date, location, blood_group, units, status) VALUES (?, ?, CURDATE(), ?, ?, ?, 'completed')");
                $stmt->execute([$donorId, $requestId, $request['hospital'], $request['blood_group'], $request['units']]);

                // Mark request as fulfilled
                $stmt = $pdo->prepare("UPDATE blood_requests SET status = 'fulfilled' WHERE id = ?");
                $stmt->execute([$requestId]);

                $pdo->commit();
                $message = 'Request accepted. Thank you for donating!';

                // Refresh data
                header("Location: donorDashboard.php?msg=" . urlencode($message));
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

// Determine who can receive this blood group (simplified)
$compatibleRecipients = [
    'A+' => 'A+, AB+',
    'A-' => 'A+, A-, AB+, AB-',
    'B+' => 'B+, AB+',
    'B-' => 'B+, B-, AB+, AB-',
    'O+' => 'A+, B+, AB+, O+',
    'O-' => 'All blood types',
    'AB+' => 'AB+',
    'AB-' => 'AB+, AB-',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Blood Connect – Donor Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet"/>
  <link href="donorDashbord.css" rel="stylesheet"/>
  <style>
    .donor-message {
      background: #e6f4ea;
      color: #1e7e34;
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 16px;
      font-size: 14px;
    }
    .donor-message.error {
      background: var(--red-light);
      color: var(--red-dark);
    }
    .nav-item { border: none; background: transparent; width: 100%; text-align: left; cursor: pointer; }
    .green-text { color: #16A34A; }
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
      <a href="donorDashboard.php" class="nav-item active">
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
        <h1 class="page-title">Donor Dashboard</h1>
        <p class="page-sub">Welcome back, <?php echo htmlspecialchars($donorName); ?>! Here's your donation overview.</p>
      </div>
      <div class="topbar-right">
        <div class="search-box">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" placeholder="Search requests..."/>
        </div>
        <button class="icon-btn notif-btn">
          <i class="fa-regular fa-bell"></i>
          <span class="notif-dot"></span>
        </button>
        <div class="user-chip">
          <div class="user-info">
            <span class="user-name"><?php echo htmlspecialchars(explode(' ', $donorName)[0]); ?></span>
            <span class="user-role">Regular Donor</span>
          </div>
          <div class="avatar"><i class="fa-solid fa-user"></i></div>
        </div>
      </div>
    </header>

    <?php if ($message): ?>
      <div class="donor-message <?php echo strpos($message, 'Failed') !== false || strpos($message, 'not eligible') !== false ? 'error' : ''; ?>"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    
    <section class="stat-cards">
      <div class="stat-card">
        <div class="stat-icon red"><i class="fa-regular fa-heart"></i></div>
        <div>
          <div class="stat-label">Total Donations</div>
          <div class="stat-value"><?php echo (int)$totalDonations; ?></div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-droplet"></i></div>
        <div>
          <div class="stat-label">Blood Group</div>
          <div class="stat-value"><?php echo htmlspecialchars($donorBloodGroup); ?></div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <div>
          <div class="stat-label">Status</div>
          <div class="stat-value <?php echo $statusClass; ?>"><?php echo htmlspecialchars($statusText); ?></div>
          <?php if (!$eligible && $daysRemaining > 0): ?>
            <div class="stat-note"><?php echo $daysRemaining; ?> days left</div>
          <?php endif; ?>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon purple"><i class="fa-regular fa-calendar"></i></div>
        <div>
          <div class="stat-label">Next Eligible</div>
          <div class="stat-value"><?php echo htmlspecialchars($nextEligibleText); ?></div>
        </div>
      </div>
    </section>

    
    <div class="mid-row">

      
      <div class="mid-left">

        
        <div class="card tracker-card">
          <div class="tracker-header">
            <div>
              <div class="card-title">Donation Eligibility Tracker</div>
              <div class="card-sub">Track your mandatory 3-month resting period between donations.</div>
            </div>
            <div class="tracker-meta">
              <span class="badge-pill <?php echo $eligible ? 'green-pill' : 'orange-pill'; ?>"><?php echo $eligible ? 'Eligible' : 'Cooling Period'; ?></span>
              <div class="last-donation">
                <span class="ld-label">Last Donation</span>
                <span class="ld-date"><?php echo htmlspecialchars($lastDonationText); ?></span>
              </div>
            </div>
          </div>

          <div class="progress-labels">
            <span>0 Days</span>
            <span class="highlight-label"><?php echo $eligible ? 'Ready to Donate' : round($daysRemaining) . ' Days Remaining'; ?></span>
            <span>90 Days</span>
          </div>
          <div class="progress-bar-bg">
            <div class="progress-bar-fill" style="width: <?php echo $progressWidth; ?>%;"></div>
          </div>

          <div class="tracker-footer">
            <span><i class="fa-regular fa-clock"></i> <?php echo $eligible ? 'You are eligible to donate now.' : "Auto-blocked until <strong>$nextEligibleText</strong>"; ?></span>
            <a href="#" class="link-btn">View Guidelines</a>
          </div>
        </div>

        
        <div class="card requests-card">
          <div class="requests-header">
            <div>
              <div class="card-title">Urgent Donation Requests</div>
              <div class="card-sub">Patients in your area matching your blood group.</div>
            </div>
            <button class="btn-outline-red">View All</button>
          </div>

          <div class="request-list">

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
              <p style="padding: 16px; color: var(--text-soft); text-align: center;">No pending requests matching your blood group in your district.</p>
            <?php endif; ?>

          </div>
        </div>

      </div>

      
      <div class="mid-right">

        
        <div class="card blood-profile-card">
          <div class="bp-header">
            <span class="card-title">Blood Profile</span>
            <a href="#" class="link-btn">Edit</a>
          </div>
          <div class="bp-body">
            <div class="blood-drop-wrap">
              <div class="blood-drop">
                <i class="fa-solid fa-droplet"></i>
                <span class="verified-dot"><i class="fa-solid fa-check"></i></span>
              </div>
              <div class="bp-group-label">Registered Blood Group</div>
              <div class="bp-group"><?php echo htmlspecialchars($donorBloodGroup); ?></div>
            </div>
            <div class="bp-info-box">
              <i class="fa-solid fa-bolt"></i>
              Your blood group is verified. You can donate to patients with <strong><?php echo htmlspecialchars($compatibleRecipients[$donorBloodGroup] ?? 'Compatible recipients'); ?></strong> blood types.
            </div>
          </div>
        </div>

        
        <div class="card quick-actions-card">
          <div class="card-title">Quick Actions</div>
          <div class="qa-list">
            <a href="donorRequest.php" class="qa-item" style="text-decoration:none;color:inherit;">
              <div class="qa-icon"><i class="fa-regular fa-calendar-check"></i></div>
              <div>
                <div class="qa-title">Donation History</div>
                <div class="qa-sub">View your past donations</div>
              </div>
            </a>
            <div class="qa-item">
              <div class="qa-icon heart-icon"><i class="fa-regular fa-heart"></i></div>
              <div>
                <div class="qa-title">Health Assessment</div>
                <div class="qa-sub">Update your medical history</div>
              </div>
            </div>
          </div>
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
