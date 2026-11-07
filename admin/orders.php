<?php
require_once 'auth_admin.php';
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    if (verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $order_id = (int)$_POST['order_id'];
        $new_status = sanitize_input($_POST['status']);
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $order_id]);
    }
}

// Single table query
$orders = $pdo->query("
    SELECT o.id, o.product_name, o.quantity, o.price, o.total_price, o.payment_method, o.mpesa_reference, o.status, u.first_name, u.last_name, u.phone 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    ORDER BY o.id DESC
")->fetchAll();

require_once '../includes/header.php';
?>
<div class="header-container" style="padding: 40px 20px; overflow-x: auto; width: 100%; margin-bottom: 20px;">
    <h1>Customer Orders & Payments</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Product Name</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total Price</th>
                <th>Payment Method</th>
                <th>M-Pesa Ref</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($orders as $o): ?>
                <tr>
                    <td>#<?php echo $o['id']; ?></td>
                    <td><?php echo htmlspecialchars($o['first_name'] . ' ' . $o['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($o['phone']); ?></td>
                    <td><strong><?php echo htmlspecialchars($o['product_name'] ?: 'Service / Plan'); ?></strong></td>
                    <td><?php echo $o['quantity']; ?></td>
                    <td>KSh <?php echo number_format($o['price'], 2); ?></td>
                    <td><strong>KSh <?php echo number_format($o['total_price'], 2); ?></strong></td>
                    <td><?php echo htmlspecialchars($o['payment_method']); ?></td>
                    <td><strong><?php echo htmlspecialchars($o['mpesa_reference'] ?: 'STK Push'); ?></strong></td>
                    <td><?php echo $o['status']; ?></td>
                    <td>
                        <form method="POST" style="display:flex; gap:5px;">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                            <select name="status" class="form-control" style="padding:4px; font-size:12px;">
                                <option value="Pending" <?php if($o['status']=='Pending') echo 'selected'; ?>>Pending</option>
                                <option value="Paid" <?php if($o['status']=='Paid') echo 'selected'; ?>>Paid</option>
                                <option value="Delivered" <?php if($o['status']=='Delivered') echo 'selected'; ?>>Delivered</option>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-primary" style="padding:4px 8px; font-size:12px;">Save</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once '../includes/footer.php'; ?>