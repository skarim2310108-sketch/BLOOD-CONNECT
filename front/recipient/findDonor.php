<?php // recipient-donors.php ?>
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
  <div class="user">Rahim Uddin (Recipient)</div>
</header>

<div class="container">

  <h1>View Nearby Donors</h1>
  <p class="subtitle">Find available blood donors near your location</p>

  <div class="filters">
    <select>
      <option>All Blood Groups</option>
      <option>A+</option>
      <option>B+</option>
      <option>O+</option>
    </select>

    <select>
      <option>All Divisions</option>
    </select>

    <input type="text" placeholder="Enter Area / Thana">

    <button class="search-btn">Search Donors</button>
  </div>

  <div class="main">

    <div class="donor-list">

      <div class="card">
        <div class="info">
          <div class="blood">B+</div>
          <div>
            <strong>Rahim Uddin</strong>
            <p>Mirpur, Dhaka</p>
            <span class="status">Available</span>
          </div>
        </div>
        <div class="contact">Contact Donor</div>
      </div>

      <div class="card">
        <div class="info">
          <div class="blood">A-</div>
          <div>
            <strong>Fatema Begum</strong>
            <p>Dhanmondi, Dhaka</p>
            <span class="status">Available</span>
          </div>
        </div>
        <div class="contact">Contact Donor</div>
      </div>

      <div class="card">
        <div class="info">
          <div class="blood">O+</div>
          <div>
            <strong>Kamal Hossain</strong>
            <p>Uttara, Dhaka</p>
            <span class="status">Available</span>
          </div>
        </div>
        <div class="contact">Contact Donor</div>
      </div>

    </div>

    <div class="map">
      <p>Interactive Map View (Coming Soon)</p>
    </div>

  </div>

</div>

</body>
</html>