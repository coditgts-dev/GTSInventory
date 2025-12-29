<?php
require 'config/auth.php';
$db = new Db();

if(!isset($_GET['id'])) {
    die("Item ID required");
}

$id = (int)$_GET['id'];

// Fetch item details including code and category
$item = $db->select("
    SELECT i.item_name, i.item_code, c.category_name
    FROM items i
    LEFT JOIN categories c ON c.id=i.category_id
    WHERE i.id=$id
")[0];

// Fetch inventory logs
$logs = $db->select("
    SELECT l.*, u.person_name
    FROM inventory_logs l
    JOIN users u ON u.id=l.user_id
    WHERE item_id=$id
    ORDER BY created_at DESC
");

// CSV headers
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=" . preg_replace('/\s+/', '_', $item['item_name']) . "_history.csv");

$out = fopen('php://output', 'w');

// Write item details as title
fputcsv($out, ["Item Name:", $item['item_name']]);
fputcsv($out, ["Item Code:", $item['item_code']]);
fputcsv($out, ["Category:", $item['category_name']]);
fputcsv($out, []); // empty row

// Column headers
fputcsv($out, ['#', 'Date', 'User', 'Action', 'Quantity', 'Remark']);

// Write logs
$i = 1;
foreach($logs as $l) {
    fputcsv($out, [
        $i,
        $l['created_at'],
        $l['person_name'],
        $l['action'],
        $l['quantity'],
        $l['remark']
    ]);
    $i++;
}

// Empty row then total count
fputcsv($out, []);
fputcsv($out, ['Total Records', count($logs)]);

fclose($out);
exit;
?>