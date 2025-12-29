<?php
require '../config/auth.php';
require '../config/admin_access.php';
$db = new Db();

$from = $_GET['from'] ?? date('Y-m-01');
$to = $_GET['to'] ?? date('Y-m-d');

$logs = $db->select("
    SELECT c.category_name, i.item_code, i.item_name, l.quantity, l.created_at, l.remark, u.person_name
    FROM inventory_logs l
    JOIN items i ON i.id=l.item_id
    JOIN categories c ON c.id=i.category_id
    JOIN users u ON u.id=l.user_id
    WHERE l.action='OUT' AND DATE(l.created_at) BETWEEN '$from' AND '$to'
    ORDER BY c.category_name, i.item_name, l.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Stock OUT report for inventory system">
<title>Stock OUT Report</title>
<link rel="icon" type="image/png" href="../images/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
</head>
<body>

<?php include '../header.php';?>

<div class="container-fluid py-5">
  <h4 class="mb-3 text-primary">Stock OUT Report</h4>

  <form method="get" class="row g-2 mb-3">
    <div class="col-auto">
      <label>From:</label>
      <input type="date" name="from" class="form-control" value="<?= $from ?>">
    </div>
    <div class="col-auto">
      <label>To:</label>
      <input type="date" name="to" class="form-control" value="<?= $to ?>">
    </div>
    <div class="col-auto align-self-end">
      <button class="btn btn-primary">Filter</button>
    </div>
  </form>

  <div class="table-responsive">
    <table id="logsTable" class="table table-bordered table-striped table-sm">
      <thead>
        <tr>
          <th>#</th>
          <th>Item Code</th>
          <th>Category</th>
          <th>Item Name</th>
          <th>Quantity</th>
          <th>Date</th>
          <th>User</th>
          <th>Remark</th>
        </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach($logs as $l): ?>
        <tr>
          <td><?= $i ?></td>
          <td><?= htmlspecialchars($l['item_code']) ?></td>
          <td><?= htmlspecialchars($l['category_name']) ?></td>
          <td><?= htmlspecialchars($l['item_name']) ?></td>
          <td><?= $l['quantity'] ?></td>
          <td><?= $l['created_at'] ?></td>
          <td><?= htmlspecialchars($l['person_name']) ?></td>
          <td><?= htmlspecialchars($l['remark']) ?></td>
        </tr>
        <?php $i++; endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
$(document).ready(function(){
    $('#logsTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'csvHtml5',
                text: 'Export CSV',
                title: 'Stock OUT Report',
                customize: function(csv){
                    let totalQty = 0;
                    $('#logsTable tbody tr').each(function(){
                        totalQty += parseInt($(this).find('td').eq(3).text());
                    });
                    csv += '\nTotal Quantity,' + totalQty;
                    return csv;
                }
            }
        ],
        order: [[1,'asc'], [2,'asc']]
    });
});
</script>

</body>
</html>
