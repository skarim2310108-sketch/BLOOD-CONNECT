<?php
// adminportal.php - Blood Connect Admin Login
session_start();

// ── DB CONFIG ──────────────────────────────────────────────────────────────
require_once '../db.php';

// ── REDIRECT IF ALREADY LOGGED IN ─────────────────────────────────────────
if (isset($_SESSION['admin_id'])) {
    header('Location: admindashboard.php');
    exit;
}

// ── HANDLE FORM SUBMISSION ─────────────────────────────────────────────────
$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = 'Please fill in both email and password.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            // Uses $pdo from ../db.php

            $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password'])) {
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);

                $_SESSION['admin_id']    = $admin['id'];
                $_SESSION['admin_name']  = $admin['name'];
                $_SESSION['admin_email'] = $admin['email'];

                header('Location: admindashboard.php');
                exit;
            } else {
                $error = 'Invalid email or password. Please try again.';
            }

        } catch (PDOException $e) {
            $error = 'Database connection failed. Please contact support.';
            error_log('DB Error [adminportal]: ' . $e->getMessage());
        }
    }
}
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

  <style>
    /* Alert styles — fits your existing CSS variables */
    .alert {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 0.85rem;
      font-weight: 500;
      text-align: left;
    }
    .alert-error {
      background-color: #fff0f2;
      color: var(--red-dark);
      border: 1px solid rgba(224, 36, 64, 0.25);
    }
    .alert i {
      font-size: 0.95rem;
      flex-shrink: 0;
    }
    .input-error {
      border-color: var(--red) !important;
    }
  </style>
</head>
<body>

  <div class="page-wrap">
    <div class="login-card">

      <div class="login-icon">
        <i class="fa-solid fa-droplet"></i>
      </div>

      <h1>Admin Portal</h1>
      <p class="subtitle">Blood Donation Management System</p>

      <?php if (!empty($error)): ?>
        <div class="alert alert-error">
          <i class="fa-solid fa-circle-exclamation"></i>
          <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <form action="adminportal.php" method="post" class="login-form" novalidate>

        <div class="form-group">
          <label for="email">
            <i class="fa-solid fa-envelope"></i> Email Address
          </label>
          <input
            type="email"
            id="email"
            name="email"
            placeholder="admin@blooddonation.com"
            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
            class="<?php echo (!empty($error)) ? 'input-error' : ''; ?>"
            required
          >
        </div>

        <div class="form-group">
          <label for="password">
            <i class="fa-solid fa-lock"></i> Password
          </label>
          <div style="position: relative;">
            <input
              type="password"
              id="password"
              name="password"
              placeholder="Enter your password"
              class="<?php echo (!empty($error)) ? 'input-error' : ''; ?>"
              style="padding-right: 44px;"
              required
            >
            <!-- Toggle password visibility -->
            <span
              onclick="togglePassword()"
              title="Show/Hide Password"
              style="
                position: absolute; right: 14px; top: 50%;
                transform: translateY(-50%);
                cursor: pointer; color: var(--gray-text);
                font-size: 0.9rem;
              "
            >
              <i class="fa-solid fa-eye" id="toggle-icon"></i>
            </span>
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block">
          <i class="fa-solid fa-right-to-bracket"></i> Sign In
        </button>

      </form>

      <a href="forgot_password.php" class="forgot-link">Forgot password?</a>

      <a href="../Role/role.php" class="change-role-link">
        <i class="fa-solid fa-chevron-left"></i> Change Role
      </a>

    </div>
  </div>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-inner">
      <div class="footer-brand">
        <span>Blood Connect</span>
        <p>&copy; <?php echo date("Y"); ?> Blood Connect. The Pulse of Precision.</p>
      </div>
      <div class="footer-links">
        <a href="#privacy">Privacy Policy</a>
        <a href="#terms">Terms of Service</a>
        <a href="#support">Contact Support</a>
      </div>
    </div>
  </footer>

  <script>
    function togglePassword() {
      const input = document.getElementById('password');
      const icon  = document.getElementById('toggle-icon');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      }
    }
  </script>

</body>
</html>