<?php
require 'config/auth.php';
$db = new Db();
$id = (int)$_GET['id'];
// Fetch item info with category and code
$item = $db->select("
    SELECT i.item_name, i.item_code, c.category_name
    FROM items i
    LEFT JOIN categories c ON c.id=i.category_id
    WHERE i.id=$id
")[0];

// Fetch logs
$logs = $db->select("
    SELECT l.*, u.person_name
    FROM inventory_logs l
    JOIN users u ON u.id=l.user_id
    WHERE item_id=$id
    ORDER BY created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($item['item_name']) ?> - History</title>
<link rel="icon" type="image/png" href="images/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>
<body>

<?php include 'header.php';?>

<div class="container-fluid py-5">
  <h4 class="mb-1 text-primary"><strong>Item Name:</strong> <?= htmlspecialchars($item['item_name']) ?></h4>
  <p>
    <strong>Item Code:</strong> <?= htmlspecialchars($item['item_code']) ?> |
    <strong>Category:</strong> <?= htmlspecialchars($item['category_name']) ?>
  </p>

  <div class="table-responsive">
    <table id="logsTable" class="table table-bordered table-striped table-sm">
      <thead>
        <tr>
          <th>#</th>
          <th>Date</th>
          <th>User</th>
          <th>Action</th>
          <th>Quantity</th>
          <th>Remark</th>
        </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach($logs as $l): ?>
        <tr>
          <td><?= $i ?></td>
          <td><?= $l['created_at'] ?></td>
          <td><?= htmlspecialchars($l['person_name']) ?></td>
          <td><?= htmlspecialchars($l['action']) ?></td>
          <td><?= $l['quantity'] ?></td>
          <td><?= htmlspecialchars($l['remark']) ?></td>
        </tr>
        <?php $i++; endforeach; ?>
      </tbody>
    </table>
  </div>
  <a href="export_item_history.php?id=<?= $id ?>" class="btn btn-success mb-2">Export CSV</a>
</div>

<script>
$(document).ready(function(){
    $('#logsTable').DataTable({
        "order": [[1, "desc"]]
    });
});
</script>

</body>
</html>
