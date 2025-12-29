<?php
require 'config/auth.php';
require 'config/admin_access.php';
$db = new Db();

// Fetch items with category, last stock action, and stock status, grouped by category
$data = $db->select("
    SELECT 
        i.item_code, 
        i.item_name, 
        c.category_name, 
        i.quantity, 
        i.min_quantity,
        i.created_at,
        IFNULL(MAX(l.created_at), '-') AS last_stock_action,
        CASE WHEN i.quantity <= i.min_quantity THEN 'Low' ELSE 'OK' END AS stock_status
    FROM items i
    LEFT JOIN categories c ON i.category_id = c.id
    LEFT JOIN inventory_logs l ON i.id = l.item_id
    GROUP BY i.id
    ORDER BY c.category_name ASC, i.item_code ASC
");

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=inventory_by_category.csv");

$out = fopen("php://output","w");

fputcsv($out, ['No','Code','Name','Category','Qty','Min Qty','Stock Status','Last Stock Action','Created At']);

$no = 1;
$currentCategory = '';

foreach($data as $r){
    // Add category header row if new category
    if($currentCategory != $r['category_name']){
        $currentCategory = $r['category_name'];
        fputcsv($out, ["", "Category: ".$currentCategory]); // Category row
    }
    fputcsv($out, [
        $no,
        $r['item_code'], 
        $r['item_name'], 
        $r['category_name'], 
        $r['quantity'], 
        $r['min_quantity'],
        $r['stock_status'],
        $r['last_stock_action'],
        $r['created_at']
    ]);
    $no++;
}

fclose($out);
exit;
?>
