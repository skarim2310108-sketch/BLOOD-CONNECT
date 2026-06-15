<?php
session_start();
require_once '../db.php';
requireRecipientLogin();

$blood_group = trim($_GET['blood_group'] ?? '');
$district = trim($_GET['district'] ?? '');
$area = trim($_GET['area'] ?? '');

// Build query dynamically
$sql = "SELECT * FROM donors WHERE status = 'available'";
$params = [];

if (!empty($blood_group)) {
    $sql .= " AND blood_group = ?";
    $params[] = $blood_group;
}
if (!empty($district)) {
    $sql .= " AND district = ?";
    $params[] = $district;
}
if (!empty($area)) {
    $sql .= " AND (address LIKE ? OR district LIKE ?)";
    $params[] = "%$area%";
    $params[] = "%$area%";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$donors = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Blood Connect - Donors</title>
  <link rel="stylesheet" href="findDonor.css">
</head>
<body>

<header>
  <h2>Blood Connect</h2>
  <div class="user"><?php echo htmlspecialchars($_SESSION['recipient_name']); ?> (Recipient)</div>
</header>

<div class="container">

  <h1>View Nearby Donors</h1>
  <p class="subtitle">Find available blood donors near your location</p>

  <form class="filters" method="GET" action="">
    <select name="blood_group">
      <option value="">All Blood Groups</option>
      <option value="A+" <?php if($blood_group == 'A+') echo 'selected'; ?>>A+</option>
      <option value="A-" <?php if($blood_group == 'A-') echo 'selected'; ?>>A-</option>
      <option value="B+" <?php if($blood_group == 'B+') echo 'selected'; ?>>B+</option>
      <option value="B-" <?php if($blood_group == 'B-') echo 'selected'; ?>>B-</option>
      <option value="O+" <?php if($blood_group == 'O+') echo 'selected'; ?>>O+</option>
      <option value="O-" <?php if($blood_group == 'O-') echo 'selected'; ?>>O-</option>
      <option value="AB+" <?php if($blood_group == 'AB+') echo 'selected'; ?>>AB+</option>
      <option value="AB-" <?php if($blood_group == 'AB-') echo 'selected'; ?>>AB-</option>
    </select>

    <select name="district">
      <option value="">All Districts</option>
      <option value="Dhaka" <?php if($district == 'Dhaka') echo 'selected'; ?>>Dhaka</option>
      <option value="Chittagong" <?php if($district == 'Chittagong') echo 'selected'; ?>>Chittagong</option>
      <option value="Rajshahi" <?php if($district == 'Rajshahi') echo 'selected'; ?>>Rajshahi</option>
      <option value="Khulna" <?php if($district == 'Khulna') echo 'selected'; ?>>Khulna</option>
      <option value="Barisal" <?php if($district == 'Barisal') echo 'selected'; ?>>Barisal</option>
      <option value="Sylhet" <?php if($district == 'Sylhet') echo 'selected'; ?>>Sylhet</option>
      <option value="Rangpur" <?php if($district == 'Rangpur') echo 'selected'; ?>>Rangpur</option>
      <option value="Mymensingh" <?php if($district == 'Mymensingh') echo 'selected'; ?>>Mymensingh</option>
    </select>

    <input type="text" name="area" placeholder="Enter Area / Thana" value="<?php echo htmlspecialchars($area); ?>">

    <button type="submit" class="search-btn">Search Donors</button>
  </form>

  <div class="main">

    <div class="donor-list">

      <?php if (count($donors) > 0): ?>
        <?php foreach ($donors as $donor): ?>
          <div class="card">
            <div class="info">
              <div class="blood"><?php echo htmlspecialchars($donor['blood_group']); ?></div>
              <div>
                <strong><?php echo htmlspecialchars($donor['name']); ?></strong>
                <p><?php echo htmlspecialchars($donor['address'] ?: $donor['district']); ?></p>
                <span class="status"><?php echo ucfirst(htmlspecialchars($donor['status'])); ?></span>
              </div>
            </div>
            <div class="contact"><?php echo htmlspecialchars($donor['phone']); ?></div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="text-align:center; color:#666; padding: 20px;">No donors found matching your criteria.</p>
      <?php endif; ?>

    </div>

    <div class="map">
      <p>Interactive Map View (Coming Soon)</p>
    </div>

  </div>

</div>

</body>
</html>
