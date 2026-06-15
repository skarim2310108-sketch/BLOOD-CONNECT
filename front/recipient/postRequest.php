<?php
session_start();
require_once '../db.php';
requireRecipientLogin();

$recipientId = $_SESSION['recipient_id'];
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_name = trim($_POST['patient_name'] ?? '');
    $age = (int)($_POST['age'] ?? 0);
    $blood_group = trim($_POST['blood_group'] ?? '');
    $units = (int)($_POST['units'] ?? 0);
    $hospital = trim($_POST['hospital'] ?? '');
    $district = trim($_POST['district'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if (empty($patient_name) || $age <= 0 || empty($blood_group) || $units <= 0 || empty($hospital) || empty($district)) {
        $error = "Please fill in all required fields correctly.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO blood_requests (recipient_id, patient_name, age, blood_group, units, hospital, district, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$recipientId, $patient_name, $age, $blood_group, $units, $hospital, $district, $address]);
            $success = "Blood request posted successfully!";
        } catch (PDOException $e) {
            $error = "Failed to post request: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Post Emergency Request</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="postRequest.css">
  <style>
    .message {
      padding: 12px 16px;
      border-radius: 8px;
      font-size: 14px;
      margin-bottom: 16px;
    }
    .message.success {
      background: #e6f4ea;
      color: #1e7e34;
    }
    .message.error {
      background: var(--red-light);
      color: var(--red-dark);
    }
  </style>
</head>
<body>


<div class="navbar">
  <div class="left">
    <a href="recipientDashboard.php"><i class="fa-solid fa-arrow-left"></i> Back To Dashboard</a>
  </div>

  <div class="center">
    <div class="logo">
      <i class="fa-solid fa-droplet"></i>
      <span>Blood Connect</span>
    </div>
    <p>The Pulse of Precision</p>
  </div>

  <div class="right">
    <strong><?php echo htmlspecialchars($_SESSION['recipient_name']); ?></strong>
    <p>Recipient Portal</p>
  </div>
</div>


<div class="main">

  <h1>Post Emergency Blood Request</h1>
  <p class="subtitle">
    Fill in the details below to submit an urgent blood request to nearby donors in Bangladesh.
  </p>

  <?php if ($success): ?>
    <div class="message success"><?php echo htmlspecialchars($success); ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="message error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form class="form-card" action="" method="POST">

    <div class="section-title">
      <span>1</span> Patient Information
    </div>

    <div class="form-grid">
      <div>
        <label>Patient Name *</label>
        <input type="text" name="patient_name" placeholder="Enter patient's full name" required value="<?php echo htmlspecialchars($_POST['patient_name'] ?? ''); ?>">
      </div>

      <div>
        <label>Age *</label>
        <input type="number" name="age" placeholder="e.g. 34" required min="1" value="<?php echo htmlspecialchars($_POST['age'] ?? ''); ?>">
      </div>

      <div>
        <label>Blood Group Needed *</label>
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

      <div>
        <label>Number of Bags/Units *</label>
        <input type="number" name="units" placeholder="e.g. 2" required min="1" value="<?php echo htmlspecialchars($_POST['units'] ?? ''); ?>">
      </div>
    </div>

    <div class="section-title">
      <span>2</span> Hospital & Location
    </div>

    <div class="form-grid">
      <div class="full">
        <label>Hospital Name *</label>
        <input type="text" name="hospital" placeholder="e.g. Dhaka Medical College Hospital" required value="<?php echo htmlspecialchars($_POST['hospital'] ?? ''); ?>">
      </div>

      <div>
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

      <div>
        <label>Hospital Address *</label>
        <input type="text" name="address" placeholder="Specific ward, building, or street" required value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>">
      </div>
    </div>

    <button type="submit" class="submit-btn">Submit Request</button>

  </form>

</div>

</body>
</html>
