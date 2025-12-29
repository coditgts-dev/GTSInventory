<?php
require_once 'Db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$db = new Db();
$user_id = (int)$_SESSION['user_id'];

$user = $db->select("
    SELECT person_name, role 
    FROM users 
    WHERE id = $user_id
    LIMIT 1
");

if (!$user) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$_SESSION['person_name'] = $user[0]['person_name'];
$_SESSION['role']        = $user[0]['role'];
?>