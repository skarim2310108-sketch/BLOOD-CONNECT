<?php
// emergencyRequest.php - Blood Connect Emergency Blood Request Admin
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Emergency Blood Request Admin | Blood Connect</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="emergencyRequest.css">
</head>
<body>

  <div class="layout">

    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-brand">
        <div class="logo">
          <i class="fa-solid fa-droplet"></i>
          <span>Blood Connect</span>
        </div>
        <p class="sidebar-subtitle">Admin Portal</p>
      </div>

      <nav class="sidebar-nav">
        <a href="admindashboard.php" class="nav-item">
          <i class="fa-solid fa-grip"></i> Dashboard
        </a>
        <a href="donorvarification.php" class="nav-item">
          <i class="fa-solid fa-user-check"></i> Donor Verification
        </a>
        <a href="emergencyRequest.php" class="nav-item active">
          <i class="fa-solid fa-clipboard-list"></i> Emergency Requests
        </a>
      </nav>

      <a href="logout.php" class="sign-out">
        <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
      </a>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <h1>Emergency Blood Request Admin</h1>
      <p class="page-subtitle">Monitor and approve emergency blood donation requests</p>

      <!-- Stat Cards -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-text">
            <h2>4</h2>
            <p>All Requests</p>
          </div>
          <div class="stat-icon icon-blue">
            <i class="fa-solid fa-circle-info"></i>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-text">
            <h2>4</h2>
            <p>Pending</p>
          </div>
          <div class="stat-icon icon-orange">
            <i class="fa-regular fa-clock"></i>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-text">
            <h2>0</h2>
            <p>Approved</p>
          </div>
          <div class="stat-icon icon-green">
            <i class="fa-solid fa-circle-check"></i>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-text">
            <h2>0</h2>
            <p>Rejected</p>
          </div>
          <div class="stat-icon icon-red">
            <i class="fa-solid fa-circle-xmark"></i>
          </div>
        </div>
      </div>

      <!-- Filter Tabs -->
      <div class="filter-tabs">
        <button class="tab active">All (4)</button>
        <button class="tab">Pending (4)</button>
        <button class="tab">Approved (0)</button>
        <button class="tab">Rejected (0)</button>
      </div>

      <!-- Request List -->
      <div class="request-list">

        <div class="request-card">
          <div class="blood-badge badge-a-pos">A+</div>
          <div class="request-info">
            <div class="request-name-row">
              <h3>HABIB HASAN</h3>
              <span class="badge badge-urgent">URGENT</span>
              <span class="badge badge-pending">PENDING</span>
            </div>
            <div class="request-details">
              <div class="detail">
                <i class="fa-solid fa-hospital"></i>
                <div>
                  <span class="detail-label">Hospital</span>
                  <span class="detail-value">Popular General Hospital</span>
                </div>
              </div>
              <div class="detail">
                <i class="fa-solid fa-user-doctor"></i>
                <div>
                  <span class="detail-label">Doctor</span>
                  <span class="detail-value">Dr Zoella</span>
                </div>
              </div>
              <div class="detail">
                <i class="fa-solid fa-phone"></i>
                <div>
                  <span class="detail-label">Contact</span>
                  <span class="detail-value">01872747477</span>
                </div>
              </div>
            </div>
            <p class="request-time">1 hour ago</p>
          </div>
          <div class="request-actions">
            <button class="btn btn-reject">Reject</button>
            <button class="btn btn-approve">Approve</button>
          </div>
        </div>

        <div class="request-card">
          <div class="blood-badge badge-o-pos">O+</div>
          <div class="request-info">
            <div class="request-name-row">
              <h3>Mona Hossain</h3>
              <span class="badge badge-urgent">URGENT</span>
              <span class="badge badge-pending">PENDING</span>
            </div>
            <div class="request-details">
              <div class="detail">
                <i class="fa-solid fa-hospital"></i>
                <div>
                  <span class="detail-label">Hospital</span>
                  <span class="detail-value">LABAID</span>
                </div>
              </div>
              <div class="detail">
                <i class="fa-solid fa-user-doctor"></i>
                <div>
                  <span class="detail-label">Doctor</span>
                  <span class="detail-value">Dr. JANNAT HOSHAN</span>
                </div>
              </div>
              <div class="detail">
                <i class="fa-solid fa-phone"></i>
                <div>
                  <span class="detail-label">Contact</span>
                  <span class="detail-value">01494-141416</span>
                </div>
              </div>
            </div>
            <p class="request-reason"><strong>Reason:</strong> Emergency surgery preparation</p>
            <p class="request-time">2 hours ago</p>
          </div>
          <div class="request-actions">
            <button class="btn btn-reject">Reject</button>
            <button class="btn btn-approve">Approve</button>
          </div>
        </div>

        <div class="request-card">
          <div class="blood-badge badge-b-pos">B+</div>
          <div class="request-info">
            <div class="request-name-row">
              <h3>Ahmed Rahman</h3>
              <span class="badge badge-urgent">URGENT</span>
              <span class="badge badge-pending">PENDING</span>
            </div>
            <div class="request-details">
              <div class="detail">
                <i class="fa-solid fa-hospital"></i>
                <div>
                  <span class="detail-label">Hospital</span>
                  <span class="detail-value">Square Hospital</span>
                </div>
              </div>
              <div class="detail">
                <i class="fa-solid fa-user-doctor"></i>
                <div>
                  <span class="detail-label">Doctor</span>
                  <span class="detail-value">Dr. Kamal</span>
                </div>
              </div>
              <div class="detail">
                <i class="fa-solid fa-phone"></i>
                <div>
                  <span class="detail-label">Contact</span>
                  <span class="detail-value">01712345678</span>
                </div>
              </div>
            </div>
            <p class="request-reason"><strong>Reason:</strong> Planned surgery</p>
            <p class="request-time">5 hours ago</p>
          </div>
          <div class="request-actions">
            <button class="btn btn-reject">Reject</button>
            <button class="btn btn-approve">Approve</button>
          </div>
        </div>

      </div>
    </main>

  </div>

</body>
</html>