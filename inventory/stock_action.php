<?php
require 'config/auth.php';
$db = new Db();
$id = (int)$_POST['item_id'];
$qty = (int)$_POST['quantity'];
$act = $_POST['action'];
$uid = $_SESSION['user_id'];

if($_SESSION['role']==='user' && $act==='IN') exit;

$item = $db->select("SELECT quantity FROM items WHERE id=$id")[0];
if($qty <=0 || ($act==='OUT' && $item['quantity']<$qty)) exit;

$db->query(
    $act==='IN' 
    ? "UPDATE items SET quantity = quantity + $qty WHERE id=$id"
    : "UPDATE items SET quantity = quantity - $qty WHERE id=$id"
);

$remark = $db->quote($_POST['remark'] ?? '');
$db->query("INSERT INTO inventory_logs (item_id,user_id,action,quantity,remark) VALUES ($id,$uid,'$act',$qty,$remark)");
?>
