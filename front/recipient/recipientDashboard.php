<?php // recipient-dashboard.php ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blood Connect Dashboard</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="reccipientDashboard.css">
</head>
<body>


<div class="navbar">
  <div class="left">
    <a href="#"><i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out</a>
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
        <strong>Rahim Uddin</strong>
        <p>Recipient Portal</p>
      </div>
      <i class="fa-regular fa-user"></i>
    </div>
  </div>
</div>


<div class="main">

  <h1>Welcome, Rahim</h1>
  <p class="subtitle">What would you like to do today?</p>

  <div class="cards">

    <div class="card">
      <div class="icon red"><i class="fa-regular fa-heart"></i></div>
      <h3>Post Emergency Request</h3>
      <p>Submit an urgent blood request to notify nearby donors immediately.</p>
      <button class="btn red">Post Request →</button>
    </div>

    <div class="card">
      <div class="icon outline"><i class="fa-solid fa-magnifying-glass"></i></div>
      <h3>View Nearby Donors</h3>
      <p>Search and contact available blood donors in your area directly.</p>
      <button class="btn outline">Find Donors →</button>
    </div>

  </div>

  <div class="stats">

    <div class="stat-card">
      <div class="stat-icon orange"><i class="fa-solid fa-wave-square"></i></div>
      <div>
        <p>Active Requests</p>
        <h2>0</h2>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon green"><i class="fa-solid fa-users"></i></div>
      <div>
        <p>Available Donors Nearby</p>
        <h2>124</h2>
      </div>
    </div>

  </div>

</div>

</body>
</html>