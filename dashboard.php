<?php
require_once 'includes/auth_middleware.php';
require_login();
require_once 'config/database.php';

$stmt = $pdo->prepare("SELECT id, total_amount, status, shipping_address, created_at FROM orders WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$_SESSION['user_id']]);
$userOrders = $stmt->fetchAll();

require_once 'includes/header.php';
?>
<div class="header-container" style="padding: 40px 20px;">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?></h1>
    <p style="color: #6b7280; margin-bottom: 30px;">Manage your orders and account details.</p>

    <div class="card">
        <h3>Your Order History</h3>
        <?php if (empty($userOrders)): ?>
            <p style="margin-top: 15px; color: #6b7280;">You haven't placed any orders yet.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Total Amount</th>
                        <th>Shipping Address</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($userOrders as $o): ?>
                        <tr>
                            <td><strong>#<?php echo $o['id']; ?></strong></td>
                            <td><strong>KSh <?php echo number_format($o['total_amount'], 2); ?></strong></td>
                            <td><?php echo htmlspecialchars($o['shipping_address'] ?? ''); ?></td>
                            <td>
                                <span style="background: #ecfdf5; color: #065f46; padding: 4px 8px; font-weight: bold; font-size: 12px; border-radius: 4px;">
                                    <?php echo htmlspecialchars($o['status'] ?? ''); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($o['created_at'] ?? ''); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>