<?php
session_start();
require_once '../db.php';

$error = '';

// Redirect if already logged in
if (!empty($_SESSION['donor_id'])) {
    redirect('donorDashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please enter email and password.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM donors WHERE email = ?");
        $stmt->execute([$email]);
        $donor = $stmt->fetch();

        if ($donor && password_verify($password, $donor['password'])) {
            $_SESSION['donor_id'] = $donor['id'];
            $_SESSION['donor_name'] = $donor['name'];
            $_SESSION['donor_email'] = $donor['email'];
            $_SESSION['donor_blood_group'] = $donor['blood_group'];
            $_SESSION['donor_district'] = $donor['district'];
            redirect('donorDashboard.php');
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Donor Portal – Blood Connect</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="donor-style.css"/>
  <style>
    .message {
      width: 100%;
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 13px;
      margin-bottom: 14px;
      text-align: center;
      background: var(--red-light);
      color: var(--red-dark);
    }
    .register-link {
      text-align: center;
      font-size: 13.5px;
      color: var(--text-soft);
      margin-top: 18px;
    }
    .register-link a {
      color: var(--red-primary);
      text-decoration: none;
      font-weight: 600;
    }
    .register-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  
  <div class="top-bar">
    <span class="top-role">Donor</span>
  </div>

  
  <main class="main">
    <div class="login-card">

      
      <div class="card-header">
        <div class="brand-icon">
          <svg viewBox="0 0 24 24" fill="white" width="24" height="24">
            <path d="M12 2C12 2 5 10.5 5 15.5C5 19.09 8.13 22 12 22C15.87 22 19 19.09 19 15.5C19 10.5 12 2 12 2Z"/>
            <line x1="12" y1="10" x2="12" y2="16" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
            <line x1="9"  y1="13" x2="15" y2="13" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
          </svg>
        </div>
        <h1 class="card-title">Donor Portal</h1>
        <p class="card-sub">Blood Donation Management System</p>
      </div>

      <?php if ($error): ?>
        <div class="message"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <form action="" method="POST">
        <div class="form-group">
          <label class="form-label">
            <svg class="label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="4" width="20" height="16" rx="2"/>
              <polyline points="2,4 12,13 22,4"/>
            </svg>
            Email Address
          </label>
          <input type="email" name="email" class="form-input" placeholder="donor@blooddonation.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"/>
        </div>

        <div class="form-group">
          <label class="form-label">
            <svg class="label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="2"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            Password
          </label>
          <input type="password" name="password" class="form-input" placeholder="Enter your password" required/>
        </div>

        
        <button type="submit" class="btn-signin">Sign In</button>
      </form>

      
      <a href="#" class="forgot-link">Forgot password?</a>

      <p class="register-link">Don't have an account? <a href="register.php">Register</a></p>

      
      <a href="../Role/role.php" class="change-role">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             width="16" height="16">
          <polyline points="15 18 9 12 15 6"/>
        </svg>
        Change Role
      </a>

    </div>
  </main>

  
  <footer class="footer">
    <div class="footer-left">
      <div class="footer-brand">Blood connect</div>
      <p class="footer-copy">© <?php echo date('Y'); ?> Blood connect, The Pulse of Precision</p>
    </div>
    <div class="footer-links">
      <a href="#">Privacy Policy</a>
      <a href="#">Terms of Service</a>
      <a href="#">Contact Support</a>
    </div>
  </footer>

</body>
</html>
