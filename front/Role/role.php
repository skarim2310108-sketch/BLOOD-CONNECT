<?php
// role.php - Blood Connect Role Selection
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Select Your Role | Blood Connect</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="role.css">
</head>
<body>

  <!-- Top Bar -->
  <header class="topbar">
    <div class="topbar-inner">
      <a href="landing.php" class="btn btn-home">Home</a>
    </div>
  </header>

  <!-- Main -->
  <div class="page-wrap">

    <div class="brand-block">
      <div class="brand-icon">
        <i class="fa-solid fa-droplet"></i>
      </div>
      <h1>Blood Connect</h1>
      <p class="brand-tagline">The Pulse of Precision</p>
    </div>

    <div class="role-card">
      <h2>Select your role</h2>
      <p class="role-subtitle">Choose how you want to access the platform</p>

      <div class="role-grid">

        <a href="adminportal.php" class="role-option">
          <div class="role-icon">
            <i class="fa-solid fa-shield-halved"></i>
          </div>
          <h3>Admin</h3>
          <p>Manage requests &amp; inventory</p>
        </a>

        <a href="donor-login.php" class="role-option">
          <div class="role-icon">
            <i class="fa-solid fa-heart"></i>
          </div>
          <h3>Donor</h3>
          <p>Manage your donations</p>
        </a>

        <a href="recipient-login.php" class="role-option">
          <div class="role-icon">
            <i class="fa-solid fa-user-check"></i>
          </div>
          <h3>Recipient</h3>
          <p>Manage blood requests</p>
        </a>

      </div>
    </div>

  </div>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-inner">
      <div class="footer-brand">
        <span>Blood connect</span>
        <p>&copy; <?php echo date("Y"); ?> Blood connect. The Pulse of Precision.</p>
      </div>
      <div class="footer-links">
        <a href="#privacy">Privacy Policy</a>
        <a href="#terms">Terms of Service</a>
        <a href="#support">Contact Support</a>
      </div>
    </div>
  </footer>

</body>
</html>