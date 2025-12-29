<?php
require 'config/Db.php';
session_start();
$db = new Db();

if ($_POST) {
    $username = $db->quote($_POST['username']);
    $password = md5($_POST['password']);

    $user = $db->select("SELECT * FROM users WHERE username=$username AND password='$password'");

    if ($user) {
        // Check if user is enabled
        if ($user[0]['login_status'] === 'disabled') {
            $error = "Your account is disabled. Contact admin.";
        } else {
            // Set session
            $_SESSION['user_id'] = $user[0]['id'];
            $_SESSION['role'] = $user[0]['role'];

            // Log successful login
            $userId = $user[0]['id'];
            $ipAddress = $_SERVER['REMOTE_ADDR'];
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            $timestamp = date('Y-m-d H:i:s');

            $db->query("INSERT INTO login_logs (user_id, login_time, ip_address, user_agent, status) 
                        VALUES ($userId, '$timestamp', '$ipAddress', '$userAgent', 'success')");

            header("Location: dashboard.php");
            exit;
        }
    } else {
        $error = "Invalid login";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GTS Inventory System Login</title>
<link rel="icon" type="image/png" href="images/favicon.ico" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container-login">
    <!-- Left side: image -->
    <div class="left-side">
        <div class="title-overlay">
            <h1>GTS</h1>
            <h2>Welcome to Inventory System</h2>
        </div>
    </div>

    <!-- Right side: login form -->
    <div class="right-side">
        <div class="login-container">
            <h3>Login</h3>
            <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="post">
                <input name="username" class="form-control" placeholder="Username" required>
                <input name="password" type="password" class="form-control" placeholder="Password" required>
                <button class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>

