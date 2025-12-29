<?php
require '../config/auth.php';
require '../config/admin_access.php';
$db = new Db();

$data = $db->select("
    SELECT i.item_code, i.item_name, i.quantity, c.category_name
    FROM items i
    LEFT JOIN categories c ON c.id=i.category_id
    ORDER BY c.category_name, i.item_name
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Current Stock Report for inventory system">
<title>Current Stock Report</title>
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
  <h4 class="mb-3 text-primary">Current Stock Report</h4>

  <div class="table-responsive">
    <table id="stockTable" class="table table-bordered table-striped table-sm">
      <thead>
        <tr>
          <th>#</th>
          <th>Item Code</th>
          <th>Category</th>
          <th>Item Name</th>
          <th>Current Qty</th>
        </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach($data as $d): ?>
        <tr>
          <td><?= $i ?></td>
          <td><?= htmlspecialchars($d['item_code']) ?></td>
          <td><?= htmlspecialchars($d['category_name']) ?></td>
          <td><?= htmlspecialchars($d['item_name']) ?></td>
          <td><?= $d['quantity'] ?></td>
        </tr>
        <?php $i++; endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
$(document).ready(function(){
    $('#stockTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'csvHtml5',
                text: 'Export CSV',
                title: 'Current Stock Report',
                customize: function(csv){
                    let totalQty = 0;
                    $('#stockTable tbody tr').each(function(){
                        totalQty += parseInt($(this).find('td').eq(4).text());
                    });
                    csv += '\nTotal Quantity,' + totalQty;
                    return csv;
                }
            }
        ],
        order: [[1,'asc'], [3,'asc']]
    });
});
</script>

</body>
</html>
