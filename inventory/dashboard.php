<?php
require 'config/auth.php';
$db = new Db();

$items = $db->select("SELECT i.*, c.category_name FROM items i LEFT JOIN categories c ON i.category_id=c.id");
$lowStock = $db->select("SELECT item_name, quantity, min_quantity FROM items WHERE quantity <= min_quantity");
$lowStockCount = count($lowStock);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GTS Inventory System</title>
<link rel="icon" type="image/png" href="images/favicon.ico" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="css/style.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>
<body>
    
<?php include 'header.php';?>

<div class="container-fluid py-5">
    <h4 class="mb-4 text-primary">Dashboard</h4>
    <div class="table-responsive mt-3">
        <table id="inventoryTable" class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $y=1; foreach($items as $i): ?>
                <tr>
                    <td><?= $y ?></td>
                    <td><?= $i['item_code'] ?></td>
                    <td><?= $i['item_name'] ?></td>
                    <td><?= $i['category_name'] ?></td>
                    <td><?= $i['description'] ?></td>
                    <td><?= $i['quantity'] ?></td>
                    <td>
                        <div class="d-grid gap-1 d-md-flex">
                            <button class="btn btn-success btn-sm act" data-id="<?= $i['id'] ?>" data-a="IN">
                                <i class="fa fa-arrow-down"></i> IN
                            </button>
                            <button class="btn btn-danger btn-sm act" data-id="<?= $i['id'] ?>" data-a="OUT">
                                <i class="fa fa-arrow-up"></i> OUT
                            </button>
                            <a href="item_history.php?id=<?= $i['id'] ?>" class="btn btn-secondary btn-sm">
                                <i class="fa fa-history"></i> History
                            </a>
                            <?php if(in_array($_SESSION['role'], ['senior_tech','manager'])): ?>
                            <a href="edit_item.php?id=<?= $i['id'] ?>" class="btn btn-primary btn-sm">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php $y++; endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if(in_array($_SESSION['role'], ['senior_tech','manager'])): ?>
    <a href="export_inventory.php" class="btn btn-outline-primary mt-3">Export CSV</a>
    <?php endif; ?>
</div>

<script>
$(document).ready(function(){
    $('#inventoryTable').DataTable();

    $('#lowStockBtn').click(function(){
        let items = `<?php foreach($lowStock as $l) { echo $l['item_name']." (".$l['quantity'].")\n"; } ?>`;
        Swal.fire({
            title: 'Low Stock Items',
            html: `<pre>${items}</pre>`,
            icon: 'warning'
        });
    });

    $('.act').click(function(){
        let action = $(this).data('a');
        let id = $(this).data('id');
        Swal.fire({
            title: 'Quantity?',
            input: 'number',
            inputAttributes: { min: 1 },
            showCancelButton: true,
            confirmButtonText: 'Submit'
        }).then((result) => {
            if(result.isConfirmed && result.value){
                let quantity = result.value;
                if(action === 'OUT'){
                    Swal.fire({
                        title: 'Remark?',
                        input: 'text',
                        showCancelButton: true,
                        confirmButtonText: 'Submit'
                    }).then((r)=>{
                        let remark = r.isConfirmed ? r.value : '';
                        submitStock(id, action, quantity, remark);
                    });
                } else {
                    submitStock(id, action, quantity, '');
                }
            }
        });
    });

    function submitStock(id, action, quantity, remark){
        $.post('stock_action.php',{
            item_id: id,
            action: action,
            quantity: quantity,
            remark: remark
        }, function(){
            Swal.fire('Success', 'Stock updated!', 'success').then(()=> location.reload());
        });
    }
});
</script>
</body>
</html>
