<?php
session_start();
require_once '../db.php';
requireRecipientLogin();

$recipientId = $_SESSION['recipient_id'];
$recipientName = $_SESSION['recipient_name'];

// Fetch active requests count
$stmt = $pdo->prepare("SELECT COUNT(*) FROM blood_requests WHERE recipient_id = ? AND status = 'pending'");
$stmt->execute([$recipientId]);
$activeRequests = $stmt->fetchColumn();

// Fetch available donors count
$stmt = $pdo->query("SELECT COUNT(*) FROM donors WHERE status IN ('available', 'verified')");
$availableDonors = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blood Connect Dashboard</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="recipientDashboard.css">
</head>
<body>


<div class="navbar">
  <div class="left">
    <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out</a>
  </div>

  <div class="center">
    <div class="logo">
      <i class="fa-solid fa-droplet"></i>
      <span>Blood Connect</span>
    </div>
    <p>The Pulse of Precision</p>
  </div>

  <div class="right">
    <div class="user">
      <div>
        <strong><?php echo htmlspecialchars($recipientName); ?></strong>
        <p>Recipient Portal</p>
      </div>
      <i class="fa-regular fa-user"></i>
    </div>
  </div>
</div>


<div class="main">

  <h1>Welcome, <?php echo htmlspecialchars(explode(' ', $recipientName)[0]); ?></h1>
  <p class="subtitle">What would you like to do today?</p>

  <div class="cards">

    <div class="card">
      <div class="icon red"><i class="fa-regular fa-heart"></i></div>
      <h3>Post Emergency Request</h3>
      <p>Submit an urgent blood request to notify nearby donors immediately.</p>
      <a href="postRequest.php" class="btn red">Post Request →</a>
    </div>

    <div class="card">
      <div class="icon outline"><i class="fa-solid fa-magnifying-glass"></i></div>
      <h3>View Nearby Donors</h3>
      <p>Search and contact available blood donors in your area directly.</p>
      <a href="findDonor.php" class="btn outline">Find Donors →</a>
    </div>

  </div>

  <div class="stats">

    <div class="stat-card">
      <div class="stat-icon orange"><i class="fa-solid fa-wave-square"></i></div>
      <div>
        <p>Active Requests</p>
        <h2><?php echo (int)$activeRequests; ?></h2>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon green"><i class="fa-solid fa-users"></i></div>
      <div>
        <p>Available Donors Nearby</p>
        <h2><?php echo (int)$availableDonors; ?></h2>
      </div>
    </div>

  </div>

</div>

</body>
</html>
