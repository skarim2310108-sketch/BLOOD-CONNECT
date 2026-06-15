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
            $stmt = $pdo->prepare("INSERT INTO recipients (name, email, phone, blood_group, district, address, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipient Registration – Blood Connect</title>
    <link rel="stylesheet" href="recipient-style.css">
    <style>
        .input-group select {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            background: #FAFBFC;
            font-family: var(--font);
            font-size: 14px;
            color: var(--text-dark);
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
        }
        .input-group select:focus {
            border-color: var(--red-primary);
            box-shadow: 0 0 0 3px rgba(192,21,42,0.09);
            background: var(--white);
        }
        .message {
            width: 100%;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 10px;
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
            margin-top: 18px;
            font-size: 13.5px;
            text-align: center;
            color: var(--text-mid);
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
    <div class="container">
        <div class="card">
            <div class="logo">
                <div class="icon">+</div>
            </div>

            <h1>Create Account</h1>
            <p class="subtitle">Register as a Recipient</p>

            <?php if ($error): ?>
                <div class="message error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="message success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="input-group">
                    <label>Full Name *</label>
                    <input type="text" name="name" placeholder="Enter your full name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                </div>

                <div class="input-group">
                    <label>Email Address *</label>
                    <input type="email" name="email" placeholder="you@example.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>

                <div class="input-group">
                    <label>Phone Number *</label>
                    <input type="text" name="phone" placeholder="01XXXXXXXXX" required value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                </div>

                <div class="input-group">
                    <label>Blood Group *</label>
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

                <div class="input-group">
                    <label>District *</label>
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

                <div class="input-group">
                    <label>Address</label>
                    <input type="text" name="address" placeholder="Area / Thana / Street" value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>">
                </div>

                <div class="input-group">
                    <label>Password *</label>
                    <input type="password" name="password" placeholder="At least 6 characters" required>
                </div>

                <div class="input-group">
                    <label>Confirm Password *</label>
                    <input type="password" name="confirm_password" placeholder="Re-enter password" required>
                </div>

                <button type="submit">Register</button>
            </form>

            <p class="login-link">Already have an account? <a href="recipient-login.php">Sign In</a></p>
        </div>
    </div>
</body>
</html>
