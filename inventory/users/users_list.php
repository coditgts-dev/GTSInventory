<?php
require '../config/auth.php';
require '../config/super_admin_only.php';
$db = new Db();

$users = $db->select("SELECT id, person_name, username, role, login_status FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Users List - GTS Inventory System</title>
<link rel="icon" type="image/png" href="../images/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<?php include '../header.php';?>

<div class="container-fluid mt-4 py-5">
    <a href="add_user.php" class="btn btn-success mt-3">Add User</a>
  <h3 class="mb-3 text-primary">Users List</h3>
  <div class="table-responsive">
    <table id="usersTable" class="table table-bordered table-striped table-sm">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Username</th>
          <th>Role</th>
          <th>Login Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach($users as $u): ?>
        <tr>
          <td><?= $i ?></td>
          <td><?= htmlspecialchars($u['person_name']) ?></td>
          <td><?= htmlspecialchars($u['username']) ?></td>
          <td><?= htmlspecialchars($u['role']) ?></td>
          <td>
            <span class="badge <?= $u['login_status']=='enabled'?'bg-success':'bg-secondary' ?>"><?= $u['login_status'] ?></span>
          </td>
          <td>
            <button class="btn btn-warning btn-sm reset-pass" data-id="<?= $u['id'] ?>">Set Password</button>
            <button class="btn btn-info btn-sm toggle-login" data-id="<?= $u['id'] ?>" data-status="<?= $u['login_status'] ?>">
              <?= $u['login_status']=='enabled'?'Disable':'Enable' ?>
            </button>
          </td>
        </tr>
        <?php $i++; endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
$(document).ready(function(){
  $('#usersTable').DataTable();

$('.reset-pass').click(function(){
    let userId = $(this).data('id');
    Swal.fire({
        title: 'Set New Password',
        html: `
            <input type="password" id="swal-password" class="swal2-input" placeholder="New password">
            <input type="checkbox" id="show-pass"> Show
        `,
        showCancelButton: true,
        confirmButtonText: 'Set Password',
        preConfirm: () => {
            const pass = document.getElementById('swal-password').value;
            if(!pass) {
                Swal.showValidationMessage('Password cannot be empty');
            }
            return pass;
        },
        didOpen: () => {
            const checkbox = document.getElementById('show-pass');
            const passwordInput = document.getElementById('swal-password');
            checkbox.addEventListener('change', function(){
                passwordInput.type = this.checked ? 'text' : 'password';
            });
        }
    }).then((result)=>{
        if(result.isConfirmed && result.value){
            let newPass = result.value;
            $.post('ajax_user_actions.php', {action:'set_password', id:userId, password:newPass}, function(res){
                Swal.fire('Success', 'Password updated successfully!', 'success');
            });
        }
    });
});


  $('.toggle-login').click(function(){
    let userId = $(this).data('id');
    let currentStatus = $(this).data('status');
    $.post('ajax_user_actions.php', {action:'toggle_login', id:userId, status:currentStatus}, function(res){
      Swal.fire('Success', 'Login status updated!', 'success').then(()=> location.reload());
    });
  });
});
</script>

</body>
</html>
