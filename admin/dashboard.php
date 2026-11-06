<?php
require_once 'auth_admin.php';
require_once '../config/database.php';

$usersCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$ordersCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$consultationsCount = $pdo->query("SELECT COUNT(*) FROM consultations WHERE status='Pending'")->fetchColumn();
$revenue = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE status='Paid'")->fetchColumn() ?: 0.00;

require_once '../includes/header.php';
?>
<div class="header-container" style="padding: 40px 20px;">
    <h1>Admin Control Panel</h1>
    <div style="display: flex; gap: 15px; margin: 20px 0;">
        <a href="dashboard.php" class="btn btn-primary">Dashboard</a>
        <a href="products.php" class="btn" style="background: #e5e7eb; color: #111;">Manage Nursery</a>
        <a href="orders.php" class="btn" style="background: #e5e7eb; color: #111;">Orders & Payments</a>
        <a href="consultations.php" class="btn" style="background: #e5e7eb; color: #333;">Consultations</a>
    </div>

    <div class="grid-3" style="margin-top: 30px;">
        <div class="card" style="border-left: 5px solid #2e7d32;">
            <p style="font-size: 13px; color: #6b7280; font-weight: bold;">TOTAL REVENUE</p>
            <h2 style="font-size: 32px; color: #2e7d32; margin-top: 10px;">KSh <?php echo number_format($revenue, 2); ?></h2>
        </div>
        <div class="card" style="border-left: 5px solid #0288d1;">
            <p style="font-size: 13px; color: #6b7280; font-weight: bold;">TOTAL USERS</p>
            <h2 style="font-size: 32px; color: #0288d1; margin-top: 10px;"><?php echo $usersCount; ?></h2>
        </div>
        <div class="card" style="border-left: 5px solid #ed6c02;">
            <p style="font-size: 13px; color: #6b7280; font-weight: bold;">PENDING CONSULTATIONS</p>
            <h2 style="font-size: 32px; color: #ed6c02; margin-top: 10px;"><?php echo $consultationsCount; ?></h2>
        </div>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
