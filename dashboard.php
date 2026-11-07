<?php
require_once 'includes/auth_middleware.php';
require_login();
require_once 'config/database.php';

$stmt = $pdo->prepare("SELECT id, product_name, quantity, price, total_price, payment_method, mpesa_reference, status, created_at FROM orders WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$_SESSION['user_id']]);
$userOrders = $stmt->fetchAll();

require_once 'includes/header.php';
?>
<div class="header-container" style="padding: 40px 20px;">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
    <p style="color: #6b7280; margin-bottom: 30px;">Manage your orders and subscription details.</p>

    <div class="card">
        <h3>Your Order History</h3>
        <?php if (empty($userOrders)): ?>
            <p style="margin-top: 15px; color: #6b7280;">You haven't placed any orders yet.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Product Name</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total Price</th>
                        <th>Payment Method</th>
                        <th>M-Pesa Ref</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($userOrders as $o): ?>
                        <tr>
                            <td><strong>#<?php echo $o['id']; ?></strong></td>
                            <td><?php echo htmlspecialchars($o['product_name'] ?: 'Service / Plan'); ?></td>
                            <td><?php echo $o['quantity']; ?></td>
                            <td>KSh <?php echo number_format($o['price'], 2); ?></td>
                            <td><strong>KSh <?php echo number_format($o['total_price'], 2); ?></strong></td>
                            <td><?php echo htmlspecialchars($o['payment_method']); ?></td>
                            <td><?php echo htmlspecialchars($o['mpesa_reference'] ?: 'STK Push'); ?></td>
                            <td>
                                <span style="background: #ecfdf5; color: #065f46; padding: 4px 8px; font-weight: bold; font-size: 12px; border-radius: 4px;">
                                    <?php echo $o['status']; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>