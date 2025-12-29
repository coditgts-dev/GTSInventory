<?php
require '../config/auth.php';
require '../config/super_admin_only.php';
$db = new Db();

$action = $_POST['action'];

if($action == 'set_password'){
    $id = intval($_POST['id']);
    $password = md5($_POST['password']); // using MD5
    $db->query("UPDATE users SET password='$password' WHERE id=$id");
    echo "ok";
}

if($action == 'toggle_login'){
    $id = intval($_POST['id']);
    $status = $_POST['status']=='enabled'?'disabled':'enabled';
    $db->query("UPDATE users SET login_status='$status' WHERE id=$id");
    echo "ok";
}
?>
