<?php
require '../config/auth.php';
require '../config/super_admin_only.php';
$db = new Db();
$message = '';

if ($_POST) {
    $name = $db->quote($_POST['person_name']);
    $username = $db->quote($_POST['username']);
    $password = md5($_POST['password']);
    $role = $db->quote($_POST['role']);

    $exists = $db->select("SELECT id FROM users WHERE username=$username");
    if ($exists) {
        $message = "<div class='alert alert-danger'>Username exists.</div>";
    } else {
        $db->query("INSERT INTO users (person_name, username, password, role) VALUES ($name, $username, '$password', $role)");
        $message = "<div class='alert alert-success'>User added successfully.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Add User - GTS Inventory System</title>
<link rel="icon" type="image/png" href="../images/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<?php include '../header.php';?>

<div class="card py-5">
    <div class="card-body">
<div class="container mt-4" style="max-width: 500px;">
    <h3 class="mb-3 text-primary">Add User</h3>
    <?= $message ?>
    <form method="post">
        <input type="text" name="person_name" class="form-control mb-2" placeholder="Person Name" required>
        <input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
        <div class="mb-2 position-relative">
            <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
            <div class="form-check mt-1">
                <input class="form-check-input" type="checkbox" id="showPassword">
                <label class="form-check-label" for="showPassword">Show Password</label>
            </div>
        </div>
        
        <script>
        document.getElementById('showPassword').addEventListener('change', function() {
            const passwordInput = document.getElementById('password');
            if(this.checked) {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        });
        </script>

        <select name="role" class="form-control mb-2" required>
            <option value="">Select Role</option>
            <option value="senior_tech">Senior Technician</option>
            <option value="tech">Technician</option>
        </select>
        <button class="btn btn-primary w-100">Add User</button>
    </form>
    <a href="users_list.php" class="btn btn-secondary mt-2 w-100">Back to Users List</a>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
