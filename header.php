<?php
// Define BASE_URL for consistent links
define('BASE_URL', '/inventory'); // adjust '/inventory' to your project folder
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">

        <!-- BRAND -->
        <a class="navbar-brand fw-semibold" href="<?= BASE_URL ?>/dashboard.php">
            Inventory System
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <!-- LEFT MENU -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <?php if($_SESSION['role'] === 'manager'): ?>
                <li class="nav-item">
                    <a class="btn btn-outline-light btn-sm me-2" href="<?= BASE_URL ?>/users/users_list.php">
                        <i class="fa-solid fa-users-gear me-1"></i> Manage Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light btn-sm me-2" href="<?= htmlspecialchars(BASE_URL) ?>/users/account_logs.php">
                        <i class="fa-solid fa-users-gear me-1"></i> Account Logs
                    </a>
                </li>
                <?php endif; ?>

                <?php if(in_array($_SESSION['role'], ['senior_tech','manager'])): ?>
                <li class="nav-item">
                    <a class="btn btn-outline-light btn-sm me-2" href="<?= BASE_URL ?>/add_item.php">
                        <i class="fa-solid fa-plus me-1"></i> Add Item
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="btn btn-outline-light btn-sm dropdown-toggle me-2" href="#" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-chart-column me-1"></i> Reports
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/reports/stock_in_report.php">Stock IN Report</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/reports/stock_out_report.php">Stock OUT Report</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/reports/current_stock_report.php">Current Stock Report</a></li>
                    </ul>
                </li>
                <?php endif; ?>

            </ul>

            <!-- RIGHT MENU -->
            <div class="d-flex align-items-center gap-3">

                <?php if($lowStockCount > 0): ?>
                    <button id="lowStockBtn" class="btn btn-danger btn-sm pulse">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i>
                        Low Stock (<?= $lowStockCount ?>)
                    </button>
                <?php endif; ?>


                <!-- USER INFO -->
                <span class="text-white d-flex align-items-center">
                    <i class="fa-solid fa-user-circle fs-5 me-2"></i>
                    <span class="lh-sm">
                        <?= htmlspecialchars($_SESSION['person_name']) ?>
                        <small class="d-block opacity-75">
                            <?= ucfirst($_SESSION['role']) ?>
                        </small>
                    </span>
                </span>

                <!-- LOGOUT -->
                <a href="javascript:void(0)" onclick="confirmLogout()" class="btn btn-outline-light btn-sm">
                    <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                </a>


            </div>
        </div>
    </div>
</nav>
<br/>
<script>
function confirmLogout() {
    Swal.fire({
        title: 'Logout?',
        text: 'Do you want to logout?',
        showCancelButton: true,
        confirmButtonText: 'Logout',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "<?= BASE_URL ?>/logout.php";
        }
    });
}
</script>

