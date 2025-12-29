<?php
require '../config/auth.php';
require '../config/super_admin_only.php';
$db = new Db();

// Fetch login logs with user info
$logs = $db->select("
    SELECT l.id, u.person_name, u.username, l.login_time, l.ip_address, l.user_agent
    FROM login_logs l
    LEFT JOIN users u ON l.user_id = u.id
    WHERE l.status='success'
    ORDER BY l.login_time DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Account Logs - GTS Inventory System</title>
<link rel="icon" type="image/png" href="../images/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<?php include '../header.php';?>

<div class="container-fluid mt-4 py-5">
  <h3 class="mb-3 text-primary">Account Login Logs</h3>
  <div class="table-responsive">
    <table id="logsTable" class="table table-bordered table-striped table-sm">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Username</th>
          <th>Login Time</th>
          <th>IP Address</th>
          <th>Browser / Device</th>
        </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach($logs as $log): ?>
        <tr>
          <td><?= $i ?></td>
          <td><?= htmlspecialchars($log['person_name'] ?? 'N/A') ?></td>
          <td><?= htmlspecialchars($log['username'] ?? 'N/A') ?></td>
          <td><?= htmlspecialchars($log['login_time']) ?></td>
          <td><?= htmlspecialchars($log['ip_address']) ?></td>
          <td><?= htmlspecialchars($log['user_agent']) ?></td>
        </tr>
        <?php $i++; endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
$(document).ready(function(){
  $('#logsTable').DataTable({
    order: [[3, 'desc']], // order by login_time descending
    pageLength: 25
  });
});
</script>

</body>
</html>
