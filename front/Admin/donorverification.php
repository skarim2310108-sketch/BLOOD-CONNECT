<?php
// ============================================================
//  donorverification.php — Blood Connect Admin Portal
//  Combined backend + frontend
// ============================================================
session_start();
require_once 'donarverificationdb.php';

// ============================================================
//  BACKEND — Handle POST actions (Verify / Reject)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action    = $_POST['action']    ?? '';
    $donor_id  = (int)($_POST['donor_id'] ?? 0);

    $allowed_actions = ['verify', 'reject'];

    if ($donor_id > 0 && in_array($action, $allowed_actions, true)) {
        $new_status = ($action === 'verify') ? 'verified' : 'rejected';

        $stmt = db()->prepare(
            "UPDATE donors SET status = ?, reviewed_at = NOW() WHERE id = ?"
        );
        $stmt->bind_param('si', $new_status, $donor_id);
        $stmt->execute();
        $stmt->close();
    }

    // PRG pattern — redirect to avoid double-submit on refresh
    header('Location: donorverification.php' .
           (isset($_GET['tab']) ? '?tab=' . urlencode($_GET['tab']) : ''));
    exit;
}

// ============================================================
//  BACKEND — Fetch donors + counts
// ============================================================
$active_tab = $_GET['tab'] ?? 'all';
$allowed_tabs = ['all', 'pending', 'verified', 'rejected'];
if (!in_array($active_tab, $allowed_tabs, true)) {
    $active_tab = 'all';
}

// Counts for stat cards and tab labels
$counts = ['pending' => 0, 'verified' => 0, 'rejected' => 0, 'all' => 0];
$count_res = db()->query(
    "SELECT status, COUNT(*) AS cnt FROM donors GROUP BY status"
);
while ($row = $count_res->fetch_assoc()) {
    if (isset($counts[$row['status']])) {
        $counts[$row['status']] = (int)$row['cnt'];
    }
}
$counts['all'] = $counts['pending'] + $counts['verified'] + $counts['rejected'];

// Fetch the donor list for the active tab
if ($active_tab === 'all') {
    $stmt = db()->prepare(
        "SELECT * FROM donors ORDER BY
            FIELD(status,'pending','verified','rejected'), created_at DESC"
    );
    $stmt->execute();
} else {
    $stmt = db()->prepare(
        "SELECT * FROM donors WHERE status = ? ORDER BY created_at DESC"
    );
    $stmt->bind_param('s', $active_tab);
    $stmt->execute();
}
$result  = $stmt->get_result();
$donors  = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// ============================================================
//  HELPERS
// ============================================================
function blood_badge_class(string $bg): string {
    $map = [
        'A+'  => 'badge-a-pos', 'A-' => 'badge-a-neg',
        'B+'  => 'badge-b-pos', 'B-' => 'badge-b-neg',
        'O+'  => 'badge-o-pos', 'O-' => 'badge-o-neg',
        'AB+' => 'badge-ab-pos','AB-'=> 'badge-ab-neg',
    ];
    return $map[$bg] ?? 'badge-b-pos';
}

function fmt_date(?string $d): string {
    if (!$d) return '&mdash;';
    try {
        return (new DateTime($d))->format('n/j/Y');
    } catch (Exception $e) {
        return htmlspecialchars($d);
    }
}

function status_badge(string $status): string {
    $map = [
        'pending'  => ['PENDING',  'badge-pending'],
        'verified' => ['VERIFIED', 'badge-verified'],
        'rejected' => ['REJECTED', 'badge-rejected'],
    ];
    [$label, $cls] = $map[$status] ?? ['UNKNOWN', 'badge-pending'];
    return "<span class=\"badge {$cls}\">{$label}</span>";
}

function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Donor Verification Portal | Blood Connect</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="donorvarification.css">
  <style>
    /* Extra status badge colours not in the original CSS */
    .badge-verified { background: var(--green-bg); color: var(--green-fg); }
    .badge-rejected { background: var(--red-bg);   color: var(--red-fg);   }

    /* Blood group badge variants */
    .badge-a-neg, .badge-b-neg, .badge-o-neg, .badge-ab-pos, .badge-ab-neg {
      background: #fbe1e4; color: var(--red);
    }

    /* Empty-state message */
    .empty-state {
      text-align: center;
      padding: 48px 24px;
      color: var(--gray-text);
      font-size: 0.9rem;
      background: var(--white);
      border: 1px solid var(--light-border);
      border-radius: var(--radius);
    }
    .empty-state i { font-size: 2rem; margin-bottom: 12px; display: block; }

    /* Feedback toast */
    .toast {
      position: fixed; bottom: 24px; right: 24px;
      background: var(--dark); color: var(--white);
      padding: 12px 22px; border-radius: 8px;
      font-size: 0.85rem; font-weight: 500;
      opacity: 0; pointer-events: none;
      transition: opacity 0.3s ease;
      z-index: 999;
    }
    .toast.show { opacity: 1; }
  </style>
</head>
<body>

<div class="layout">

  <!-- ========================================================
       Sidebar
  ======================================================== -->
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
      <a href="donorverification.php" class="nav-item active">
        <i class="fa-solid fa-user-check"></i> Donor Verification
      </a>
      <a href="emergencyRequest.php" class="nav-item">
        <i class="fa-solid fa-clipboard-list"></i> Emergency Requests
      </a>
    </nav>

    <a href="logout.php" class="sign-out">
      <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
    </a>
  </aside>

  <!-- ========================================================
       Main Content
  ======================================================== -->
  <main class="main-content">
    <h1>Donor Verification Portal</h1>
    <p class="page-subtitle">Review and verify blood donor accounts to ensure platform integrity</p>

    <!-- ── Stat Cards ──────────────────────────────────────── -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-text">
          <h2><?= $counts['pending'] ?></h2>
          <p>Pending Verifications</p>
        </div>
        <div class="stat-icon icon-orange">
          <i class="fa-regular fa-clock"></i>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-text">
          <h2><?= $counts['verified'] ?></h2>
          <p>Verified Accounts</p>
        </div>
        <div class="stat-icon icon-green">
          <i class="fa-solid fa-circle-check"></i>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-text">
          <h2><?= $counts['rejected'] ?></h2>
          <p>Rejected Accounts</p>
        </div>
        <div class="stat-icon icon-red">
          <i class="fa-solid fa-circle-xmark"></i>
        </div>
      </div>
    </div>

    <!-- ── Filter Tabs ─────────────────────────────────────── -->
    <div class="filter-tabs">
      <?php
      $tabs = [
        'all'      => "All ({$counts['all']})",
        'pending'  => "Pending ({$counts['pending']})",
        'verified' => "Verified ({$counts['verified']})",
        'rejected' => "Rejected ({$counts['rejected']})",
      ];
      foreach ($tabs as $key => $label): ?>
        <a href="?tab=<?= $key ?>"
           class="tab <?= $active_tab === $key ? 'active' : '' ?>">
          <?= $label ?>
        </a>
      <?php endforeach; ?>
    </div>

    <!-- ── Donor List ──────────────────────────────────────── -->
    <div class="donor-list">
      <?php if (empty($donors)): ?>
        <div class="empty-state">
          <i class="fa-solid fa-inbox"></i>
          No donors found in this category.
        </div>
      <?php else: ?>
        <?php foreach ($donors as $d): ?>
          <?php
            $is_expired  = !empty($d['nid_expiry']) && strtotime($d['nid_expiry']) < time();
          ?>
          <div class="donor-card">

            <!-- Blood group badge -->
            <div class="blood-badge <?= blood_badge_class($d['blood_group']) ?>">
              <?= e($d['blood_group']) ?>
            </div>

            <!-- Donor info -->
            <div class="donor-info">
              <div class="donor-name-row">
                <h3><?= e($d['full_name']) ?></h3>
                <?= status_badge($d['status']) ?>
              </div>

              <div class="donor-details">
                <div class="detail">
                  <span class="detail-label">Donor ID</span>
                  <span class="detail-value"><?= e($d['donor_id']) ?></span>
                </div>
                <div class="detail">
                  <span class="detail-label">Age</span>
                  <span class="detail-value"><?= (int)$d['age'] ?></span>
                </div>
                <div class="detail">
                  <span class="detail-label">Registered</span>
                  <span class="detail-value"><?= fmt_date($d['registered_at']) ?></span>
                </div>
                <?php if (!empty($d['nid_expiry'])): ?>
                <div class="detail">
                  <span class="detail-label">NID Expiry</span>
                  <span class="detail-value <?= $is_expired ? 'text-danger' : '' ?>">
                    <?= fmt_date($d['nid_expiry']) ?>
                  </span>
                </div>
                <?php endif; ?>
                <div class="detail">
                  <span class="detail-label">Last Donation</span>
                  <span class="detail-value"><?= fmt_date($d['last_donation']) ?></span>
                </div>
              </div>

              <div class="donor-email">
                <i class="fa-regular fa-envelope"></i>
                <?= e($d['email']) ?>
              </div>
            </div>

            <!-- Actions — only show for pending donors -->
            <?php if ($d['status'] === 'pending'): ?>
            <div class="donor-actions">
              <!-- Reject -->
              <form method="POST" action="donorverification.php?tab=<?= e($active_tab) ?>"
                    onsubmit="return confirmAction('reject', '<?= e($d['full_name']) ?>')">
                <input type="hidden" name="action"    value="reject">
                <input type="hidden" name="donor_id"  value="<?= (int)$d['id'] ?>">
                <button type="submit" class="btn btn-reject">Reject</button>
              </form>

              <!-- Verify -->
              <form method="POST" action="donorverification.php?tab=<?= e($active_tab) ?>"
                    onsubmit="return confirmAction('verify', '<?= e($d['full_name']) ?>')">
                <input type="hidden" name="action"    value="verify">
                <input type="hidden" name="donor_id"  value="<?= (int)$d['id'] ?>">
                <button type="submit" class="btn btn-verify">Verify</button>
              </form>
            </div>
            <?php endif; ?>

          </div><!-- /.donor-card -->
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </main>
</div>

<!-- Feedback Toast -->
<div class="toast" id="toast"></div>

<script>
  function confirmAction(action, name) {
    const msg = action === 'verify'
      ? `Verify donor "${name}"?`
      : `Reject donor "${name}"? This can be reviewed later.`;
    return confirm(msg);
  }

  // Simple toast for any URL param feedback (optional extension)
  const params = new URLSearchParams(location.search);
  if (params.get('success')) {
    const toast = document.getElementById('toast');
    toast.textContent = decodeURIComponent(params.get('success'));
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
  }
</script>

</body>
</html>
