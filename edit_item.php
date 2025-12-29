<?php
require 'config/auth.php';
require 'config/admin_access.php';
$db = new Db();

$id = (int)$_GET['id'];
$item = $db->select("SELECT * FROM items WHERE id=$id")[0];
$categories = $db->select("SELECT * FROM categories");

if($_POST){
    $category_id = (int)$_POST['category_id'];
    $item_name = trim($_POST['item_name']);
    $description = trim($_POST['description']);
    $min_quantity = (int)$_POST['min_quantity'];

    $db->query("
        UPDATE items SET 
        category_id=$category_id,
        item_name=".$db->quote($item_name).",
        description=".$db->quote($description).",
        min_quantity=$min_quantity
        WHERE id=$id
    ");
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Edit inventory item details">
<meta name="author" content="Inventory System">
<title>Edit Item</title>

<link rel="icon" type="image/png" href="images/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<?php include 'header.php';?>

<!-- CONTENT -->
<div class="container py-5 d-flex justify-content-center">
  <div class="card shadow-sm p-4" style="max-width: 600px; width:100%;">
    <h4 class="mb-3 text-primary text-center">Edit Item</h4>

    <form method="post">
      <div class="mb-3">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-select" required>
          <?php foreach($categories as $c): ?>
          <option value="<?= $c['id'] ?>" <?= $item['category_id']==$c['id']?'selected':'' ?>>
            <?= htmlspecialchars($c['category_name']) ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Item Name</label>
        <input type="text" name="item_name" class="form-control" value="<?= htmlspecialchars($item['item_name']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="2"><?= htmlspecialchars($item['description']) ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Low Stock Alert Level</label>
        <input type="number" name="min_quantity" class="form-control" value="<?= $item['min_quantity'] ?>" min="0">
      </div>

      <button class="btn btn-primary w-100">Update Item</button>
    </form>

    <a href="dashboard.php" class="btn btn-secondary w-100 mt-2">Back</a>
  </div>
</div>

</body>
</html>
