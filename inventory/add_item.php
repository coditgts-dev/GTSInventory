<?php
require 'config/auth.php';
require 'config/admin_access.php';
$db = new Db();
$message = '';

$last = $db->select("SELECT item_code FROM items ORDER BY id DESC LIMIT 1");
$next = $last ? intval(substr($last[0]['item_code'],-4)) + 1 : 1;
$item_code = "ITM-" . str_pad($next,4,'0',STR_PAD_LEFT);

$categories = $db->select("SELECT * FROM categories");

if($_SERVER['REQUEST_METHOD']==='POST'){
    $category_id = (int)$_POST['category_id'];
    $item_name = trim($_POST['item_name']);
    $description = trim($_POST['description']);
    $quantity = (int)$_POST['quantity'];
    $min_quantity = (int)$_POST['min_quantity'];

    if($item_name===''){
        $message = "<div class='alert alert-danger'>Item name required.</div>";
    } else {
        $code = $db->quote($item_code);
        $exists = $db->select("SELECT id FROM items WHERE item_code=$code");
        if($exists){
            $message = "<div class='alert alert-danger'>Item code exists.</div>";
        } else {
            $db->query("
                INSERT INTO items (item_code,item_name,description,quantity,min_quantity,category_id)
                VALUES ($code,".$db->quote($item_name).",".$db->quote($description).",$quantity,$min_quantity,$category_id)
            ");
            $message = "<div class='alert alert-success'>Item added successfully.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Add Item - GTS Inventory System</title>
<link rel="icon" type="image/png" href="images/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php';?>

<?php
// Fetch last 10 items
$lastItems = $db->select("SELECT item_code, item_name, quantity FROM items ORDER BY id DESC LIMIT 10");
?>
<div class="container mt-5 py-5">
    <h4 class="mb-4 text-primary">Add Item</h4>
    <?= $message ?>
    <div class="row">
        <!-- Left: Form (75%) -->
        <div class="col-lg-9 mb-4">
            <div class="card shadow-sm p-4">
                <form method="post">
    <div class="row">
        <!-- Left Column -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Item Code</label>
            <input type="text" name="item_code" class="form-control" value="<?= $item_code ?>" readonly>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-select" required>
                <option value="">Select Category</option>
                <?php foreach($categories as $c): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['category_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Item Name</label>
            <input type="text" name="item_name" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="1"></textarea>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Initial Quantity</label>
            <input type="number" name="quantity" class="form-control" value="0" min="0">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Low Stock Alert</label>
            <input type="number" name="min_quantity" class="form-control" value="10" min="0">
        </div>
    </div>
    <button class="btn btn-primary w-100">Save Item</button>
</form>

            </div>
        </div>

        <!-- Right: Last 10 Items (25%) -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm p-3">
                <h5 class="text-center text-primary mb-3">Last 10 Items</h5>
                <table class="table table-sm table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($lastItems as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['item_code']) ?></td>
                            <td><?= htmlspecialchars($item['item_name']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($lastItems)): ?>
                        <tr><td colspan="3" class="text-center">No items yet</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
