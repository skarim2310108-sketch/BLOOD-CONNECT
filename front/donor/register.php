<?php
session_start();
require_once '../db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $blood_group = trim($_POST['blood_group'] ?? '');
    $district = trim($_POST['district'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($email) || empty($phone) || empty($blood_group) || empty($district) || empty($password)) {
        $error = "Please fill in all required fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO donors (name, email, phone, blood_group, district, address, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $name,
                $email,
                $phone,
                $blood_group,
                $district,
                $address,
                password_hash($password, PASSWORD_DEFAULT)
            ]);
            $success = "Registration successful! You can now sign in.";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "This email is already registered.";
            } else {
                $error = "Registration failed: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Donor Registration – Blood Connect</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="donor-style.css"/>
  <style>
    .form-group select {
      width: 100%;
      padding: 12px 16px;
      border: 1.5px solid var(--border);
      border-radius: var(--radius-input);
      background: var(--input-bg);
      font-family: var(--font);
      font-size: 14.5px;
      color: var(--text-dark);
      outline: none;
      transition: border-color 0.18s, box-shadow 0.18s;
    }
    .form-group select:focus {
      border-color: var(--red-primary);
      box-shadow: 0 0 0 3px rgba(192,21,42,0.09);
      background: var(--white);
    }
    .message {
      width: 100%;
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 13px;
      margin-bottom: 14px;
      text-align: center;
    }
    .message.error {
      background: var(--red-light);
      color: var(--red-dark);
    }
    .message.success {
      background: #e6f4ea;
      color: #1e7e34;
    }
    .login-link {
      text-align: center;
      font-size: 13.5px;
      color: var(--text-soft);
      margin-top: 18px;
    }
    .login-link a {
      color: var(--red-primary);
      text-decoration: none;
      font-weight: 600;
    }
    .login-link a:hover {
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
        <h1 class="card-title">Create Donor Account</h1>
        <p class="card-sub">Blood Donation Management System</p>
      </div>

      <?php if ($error): ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="message success"><?php echo htmlspecialchars($success); ?></div>
      <?php endif; ?>

      <form action="" method="POST">
        <div class="form-group">
          <label class="form-label">
            <svg class="label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
              <circle cx="12" cy="7" r="4"/>
            </svg>
            Full Name *
          </label>
          <input type="text" name="name" class="form-input" placeholder="Enter your full name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
        </div>

        <div class="form-group">
          <label class="form-label">
            <svg class="label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="4" width="20" height="16" rx="2"/>
              <polyline points="2,4 12,13 22,4"/>
            </svg>
            Email Address *
          </label>
          <input type="email" name="email" class="form-input" placeholder="donor@blooddonation.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>

        <div class="form-group">
          <label class="form-label">
            <svg class="label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2z"/>
            </svg>
            Phone Number *
          </label>
          <input type="text" name="phone" class="form-input" placeholder="01XXXXXXXXX" required value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
        </div>

        <div class="form-group">
          <label class="form-label">
            <svg class="label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 2C12 2 5 10.5 5 15.5C5 19.09 8.13 22 12 22C15.87 22 19 19.09 19 15.5C19 10.5 12 2 12 2Z"/>
              <line x1="12" y1="10" x2="12" y2="16" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
            Blood Group *
          </label>
          <select name="blood_group" required>
            <option value="">Select blood group</option>
            <option value="A+" <?php if(($_POST['blood_group'] ?? '') == 'A+') echo 'selected'; ?>>A+</option>
            <option value="A-" <?php if(($_POST['blood_group'] ?? '') == 'A-') echo 'selected'; ?>>A-</option>
            <option value="B+" <?php if(($_POST['blood_group'] ?? '') == 'B+') echo 'selected'; ?>>B+</option>
            <option value="B-" <?php if(($_POST['blood_group'] ?? '') == 'B-') echo 'selected'; ?>>B-</option>
            <option value="O+" <?php if(($_POST['blood_group'] ?? '') == 'O+') echo 'selected'; ?>>O+</option>
            <option value="O-" <?php if(($_POST['blood_group'] ?? '') == 'O-') echo 'selected'; ?>>O-</option>
            <option value="AB+" <?php if(($_POST['blood_group'] ?? '') == 'AB+') echo 'selected'; ?>>AB+</option>
            <option value="AB-" <?php if(($_POST['blood_group'] ?? '') == 'AB-') echo 'selected'; ?>>AB-</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">
            <svg class="label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
              <circle cx="12" cy="10" r="3"/>
            </svg>
            District *
          </label>
          <select name="district" required>
            <option value="">Select district</option>
            <option value="Dhaka" <?php if(($_POST['district'] ?? '') == 'Dhaka') echo 'selected'; ?>>Dhaka</option>
            <option value="Chittagong" <?php if(($_POST['district'] ?? '') == 'Chittagong') echo 'selected'; ?>>Chittagong</option>
            <option value="Rajshahi" <?php if(($_POST['district'] ?? '') == 'Rajshahi') echo 'selected'; ?>>Rajshahi</option>
            <option value="Khulna" <?php if(($_POST['district'] ?? '') == 'Khulna') echo 'selected'; ?>>Khulna</option>
            <option value="Barisal" <?php if(($_POST['district'] ?? '') == 'Barisal') echo 'selected'; ?>>Barisal</option>
            <option value="Sylhet" <?php if(($_POST['district'] ?? '') == 'Sylhet') echo 'selected'; ?>>Sylhet</option>
            <option value="Rangpur" <?php if(($_POST['district'] ?? '') == 'Rangpur') echo 'selected'; ?>>Rangpur</option>
            <option value="Mymensingh" <?php if(($_POST['district'] ?? '') == 'Mymensingh') echo 'selected'; ?>>Mymensingh</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">
            <svg class="label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
              <polyline points="9,22 9,12 15,12 15,22"/>
            </svg>
            Address
          </label>
          <input type="text" name="address" class="form-input" placeholder="Area / Thana / Street" value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>">
        </div>

        <div class="form-group">
          <label class="form-label">
            <svg class="label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="2"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            Password *
          </label>
          <input type="password" name="password" class="form-input" placeholder="At least 6 characters" required>
        </div>

        <div class="form-group">
          <label class="form-label">
            <svg class="label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="2"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            Confirm Password *
          </label>
          <input type="password" name="confirm_password" class="form-input" placeholder="Re-enter password" required>
        </div>

        <button type="submit" class="btn-signin">Register</button>
      </form>

      <p class="login-link">Already have an account? <a href="donor-login.php">Sign In</a></p>

      <a href="../Role/role.php" class="change-role">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
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
