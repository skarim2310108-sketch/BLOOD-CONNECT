<?php
// adminportal.php - Blood Connect Admin Login
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Portal | Blood Connect</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="adminportal.css">
</head>
<body>

  <div class="page-wrap">

    <div class="login-card">
      <div class="login-icon">
        <i class="fa-solid fa-droplet"></i>
      </div>

      <h1>Admin Portal</h1>
      <p class="subtitle">Blood Donation Management System</p>

      <form action="#" method="post" class="login-form">
        <div class="form-group">
          <label for="email"><i class="fa-solid fa-envelope"></i> Email Address</label>
          <input type="email" id="email" name="email" placeholder="admin@blooddonation.com" required>
        </div>

        <div class="form-group">
          <label for="password"><i class="fa-solid fa-lock"></i> Password</label>
          <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
      </form>

      <a href="#forgot-password" class="forgot-link">Forgot password?</a>

      <a href="#change-role" class="change-role-link">
        <i class="fa-solid fa-chevron-left"></i> Change Role
      </a>
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