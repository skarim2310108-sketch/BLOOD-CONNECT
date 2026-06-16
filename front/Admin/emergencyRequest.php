<?php
// ============================================================
// emergencyRequest.php — Blood Connect Emergency Request Admin
// Requires: XAMPP (Apache + MySQL), blood_connect database
// ============================================================

// ── DB Connection ────────────────────────────────────────────
require_once '../db.php';

// ── Handle Approve / Reject POST ────────────────────────────
$action_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id'])) {
    $new_status = ($_POST['action'] === 'approve') ? 'approved' : 'rejected';
    $req_id     = (int) $_POST['id'];

    $stmt = $pdo->prepare(
        "UPDATE emergency_requests SET status = ? WHERE id = ?"
    );
    $stmt->execute([$new_status, $req_id]);

    // Redirect to avoid form re-submission on refresh
    header('Location: emergencyRequest.php');
    exit;
}

// ── Active Tab Filter ────────────────────────────────────────
$allowed_tabs = ['all', 'pending', 'approved', 'rejected'];
$active_tab   = isset($_GET['tab']) && in_array($_GET['tab'], $allowed_tabs)
                ? $_GET['tab']
                : 'all';

// ── Fetch Counts ─────────────────────────────────────────────
$counts = [];
$rows   = $pdo->query(
    "SELECT status, COUNT(*) AS cnt FROM emergency_requests GROUP BY status"
)->fetchAll(PDO::FETCH_ASSOC);

$counts = ['all' => 0, 'pending' => 0, 'approved' => 0, 'rejected' => 0];
foreach ($rows as $r) {
    $counts[$r['status']] = (int) $r['cnt'];
    $counts['all'] += (int) $r['cnt'];
}

// ── Fetch Requests (filtered) ────────────────────────────────
if ($active_tab === 'all') {
    $stmt = $pdo->query(
        "SELECT * FROM emergency_requests ORDER BY requested_at DESC"
    );
} else {
    $stmt = $pdo->prepare(
        "SELECT * FROM emergency_requests WHERE status = ? ORDER BY requested_at DESC"
    );
    $stmt->execute([$active_tab]);
}
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ── Helper: human-readable time ago ─────────────────────────
function time_ago(string $datetime): string {
    $diff = time() - strtotime($datetime);
    if ($diff < 60)          return 'Just now';
    if ($diff < 3600)        return (int)($diff/60)   . ' min ago';
    if ($diff < 86400)       return (int)($diff/3600) . ' hour' . ((int)($diff/3600) > 1 ? 's' : '') . ' ago';
    return (int)($diff/86400) . ' day' . ((int)($diff/86400) > 1 ? 's' : '') . ' ago';
}

// ── Helper: blood badge CSS class ───────────────────────────
function blood_class(string $type): string {
    $map = [
        'A+' => 'badge-a-pos', 'A-' => 'badge-a-neg',
        'B+' => 'badge-b-pos', 'B-' => 'badge-b-neg',
        'O+' => 'badge-o-pos', 'O-' => 'badge-o-neg',
        'AB+' => 'badge-ab-pos', 'AB-' => 'badge-ab-neg',
    ];
    return $map[$type] ?? 'badge-o-pos';
}
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

  <!-- ── Sidebar ─────────────────────────────────────────── -->
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
      <a href="donorverification.php" class="nav-item">
        <i class="fa-solid fa-user-check"></i> Donor Verification
      </a>
      <!-- <a href="emergencyRequest.php" class="nav-item active">
        <i class="fa-solid fa-clipboard-list"></i> Emergency Requests
      </a> -->
    </nav>

    <a href="logout.php" class="sign-out">
      <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
    </a>
  </aside>

  <!-- ── Main Content ─────────────────────────────────────── -->
  <main class="main-content">
    <h1>Emergency Blood Request Admin</h1>
    <p class="page-subtitle">Monitor and approve emergency blood donation requests</p>

    <!-- Stat Cards -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-text">
          <h2><?= $counts['all'] ?></h2>
          <p>All Requests</p>
        </div>
        <div class="stat-icon icon-blue">
          <i class="fa-solid fa-circle-info"></i>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-text">
          <h2><?= $counts['pending'] ?></h2>
          <p>Pending</p>
        </div>
        <div class="stat-icon icon-orange">
          <i class="fa-regular fa-clock"></i>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-text">
          <h2><?= $counts['approved'] ?></h2>
          <p>Approved</p>
        </div>
        <div class="stat-icon icon-green">
          <i class="fa-solid fa-circle-check"></i>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-text">
          <h2><?= $counts['rejected'] ?></h2>
          <p>Rejected</p>
        </div>
        <div class="stat-icon icon-red">
          <i class="fa-solid fa-circle-xmark"></i>
        </div>
      </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
      <?php foreach (['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $key => $label): ?>
        <a href="?tab=<?= $key ?>"
           class="tab <?= $active_tab === $key ? 'active' : '' ?>">
          <?= $label ?> (<?= $counts[$key] ?>)
        </a>
      <?php endforeach; ?>
    </div>

    <!-- Request List -->
    <div class="request-list">

      <?php if (empty($requests)): ?>
        <div class="empty-state">
          <i class="fa-solid fa-inbox"></i>
          <p>No <?= $active_tab !== 'all' ? $active_tab : '' ?> requests found.</p>
        </div>

      <?php else: ?>
        <?php foreach ($requests as $req): ?>
          <div class="request-card">

            <!-- Blood Type Badge -->
            <div class="blood-badge <?= blood_class($req['blood_type']) ?>">
              <?= htmlspecialchars($req['blood_type']) ?>
            </div>

            <!-- Info -->
            <div class="request-info">
              <div class="request-name-row">
                <h3><?= htmlspecialchars(strtoupper($req['patient_name'])) ?></h3>

                <?php if ($req['urgency'] === 'URGENT'): ?>
                  <span class="badge badge-urgent">URGENT</span>
                <?php endif; ?>

                <?php
                  $status_class = [
                    'pending'  => 'badge-pending',
                    'approved' => 'badge-approved',
                    'rejected' => 'badge-rejected',
                  ][$req['status']] ?? 'badge-pending';
                ?>
                <span class="badge <?= $status_class ?>">
                  <?= strtoupper($req['status']) ?>
                </span>
              </div>

              <div class="request-details">
                <div class="detail">
                  <i class="fa-solid fa-hospital"></i>
                  <div>
                    <span class="detail-label">Hospital</span>
                    <span class="detail-value"><?= htmlspecialchars($req['hospital']) ?></span>
                  </div>
                </div>
                <div class="detail">
                  <i class="fa-solid fa-user-doctor"></i>
                  <div>
                    <span class="detail-label">Doctor</span>
                    <span class="detail-value"><?= htmlspecialchars($req['doctor_name']) ?></span>
                  </div>
                </div>
                <div class="detail">
                  <i class="fa-solid fa-phone"></i>
                  <div>
                    <span class="detail-label">Contact</span>
                    <span class="detail-value"><?= htmlspecialchars($req['contact']) ?></span>
                  </div>
                </div>
              </div>

              <?php if (!empty($req['reason'])): ?>
                <p class="request-reason">
                  <strong>Reason:</strong> <?= htmlspecialchars($req['reason']) ?>
                </p>
              <?php endif; ?>

              <p class="request-time"><?= time_ago($req['requested_at']) ?></p>
            </div>

            <!-- Actions (only show for pending) -->
            <?php if ($req['status'] === 'pending'): ?>
              <div class="request-actions">
                <form method="POST" action="emergencyRequest.php"
                      onsubmit="return confirm('Reject this request?')">
                  <input type="hidden" name="id"     value="<?= (int)$req['id'] ?>">
                  <input type="hidden" name="action" value="reject">
                  <button type="submit" class="btn btn-reject">Reject</button>
                </form>

                <form method="POST" action="emergencyRequest.php"
                      onsubmit="return confirm('Approve this request?')">
                  <input type="hidden" name="id"     value="<?= (int)$req['id'] ?>">
                  <input type="hidden" name="action" value="approve">
                  <button type="submit" class="btn btn-approve">Approve</button>
                </form>
              </div>
            <?php endif; ?>

          </div>
        <?php endforeach; ?>
      <?php endif; ?>

    </div>
  </main>

</div>

</body>
</html>
