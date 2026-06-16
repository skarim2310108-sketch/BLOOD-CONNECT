<?php
session_start();
require_once '../db.php';

$error = '';

// Redirect if already logged in
if (!empty($_SESSION['recipient_id'])) {
    redirect('recipientDashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please enter email and password.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM recipients WHERE email = ?");
        $stmt->execute([$email]);
        $recipient = $stmt->fetch();

        if ($recipient && password_verify($password, $recipient['password'])) {
            $_SESSION['recipient_id'] = $recipient['id'];
            $_SESSION['recipient_name'] = $recipient['name'];
            $_SESSION['recipient_email'] = $recipient['email'];
            redirect('recipientDashboard.php');
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipient Portal – Blood Connect</title>
    <link rel="stylesheet" href="recipient-style.css">
    <style>
        .message {
            width: 100%;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 10px;
            text-align: center;
            background: var(--red-light);
            color: var(--red-dark);
        }
        .register-link {
            margin-top: 18px;
            font-size: 13.5px;
            text-align: center;
            color: var(--text-mid);
        }
        .register-link a {
            color: var(--red-primary);
            text-decoration: none;
            font-weight: 600;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
        .change-role {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 14px;
            font-size: 13.5px;
            color: var(--text-mid);
            text-decoration: none;
            transition: color 0.2s;
        }
        .change-role:hover {
            color: var(--red-primary);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">

            <div class="logo">
                <div class="icon">+</div>
            </div>

            <h1>Recipient Portal</h1>
            <p class="subtitle">Blood Donation Management System</p>

            <?php if ($error): ?>
                <div class="message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="input-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="you@example.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>

                <button type="submit">Sign In</button>
            </form>

            <a href="#" class="forgot">Forgot password?</a>

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
    </div>

</body>
</html>