<?php
require_once 'includes/auth_middleware.php';
require_login();
require_once 'config/database.php';

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_payment'])) {
    if (verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $method = sanitize_input($_POST['payment_method']);
        $mpesa_ref = sanitize_input($_POST['mpesa_reference'] ?? '');
        
        if (!empty($_SESSION['cart'])) {
            $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
            $stmtProducts = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
            $products = $stmtProducts->fetchAll();
            
            // Orders table uses product_name while products table uses name
            $stmtInsert = $pdo->prepare("
                INSERT INTO orders (user_id, product_name, quantity, price, total_price, total_amount, payment_method, mpesa_reference, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')
            ");

            $grandTotal = 0;
            foreach ($products as $p) {
                $qty = (int)$_SESSION['cart'][$p['id']];
                $price = (float)$p['price'];
                $itemSubtotal = $price * $qty;
                $grandTotal += $itemSubtotal;

                // Insert directly into orders table
                $stmtInsert->execute([
                    $_SESSION['user_id'],
                    $p['name'],
                    $qty,
                    $price,
                    $itemSubtotal,
                    $itemSubtotal,
                    $method,
                    $mpesa_ref
                ]);
            }

            $_SESSION['cart'] = [];
            $success = "Order submitted successfully!";
        }
    }
}

require_once 'includes/header.php';
?>
<div class="header-container" style="padding: 40px 20px; max-width: 600px;">
    <h1>M-Pesa Checkout</h1>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?> <a href="dashboard.php">View order history</a></div>
    <?php else: ?>
        <div class="card" style="margin-top: 20px;">
            <div style="display: flex; gap: 20px; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                <label><input type="radio" name="pay_type" onclick="togglePaymentMethod('stk')" checked> STK Push</label>
                <label><input type="radio" name="pay_type" onclick="togglePaymentMethod('paybill')"> Manual Paybill</label>
            </div>

            <form method="POST" id="stkMethodBox">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="payment_method" value="M-Pesa STK Push">
                <div class="form-group">
                    <label>M-Pesa Phone Number</label>
                    <input type="text" name="phone" placeholder="0712345678" required class="form-control">
                </div>
                <button type="submit" name="process_payment" class="btn btn-primary" style="width: 100%;">Send STK Push Request</button>
            </form>

            <form method="POST" id="paybillMethodBox" style="display: none;">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="payment_method" value="M-Pesa Paybill">
                <div style="background: #f4f8f3; padding: 15px; border-radius: 8px; margin-bottom: 15px; font-size: 14px;">
                    <p>1. Go to M-Pesa Menu -> Lipa na M-Pesa -> Paybill</p>
                    <p>2. Business Number: <strong>522522</strong></p>
                    <p>3. Account Number: <strong>1342666798</strong></p>
                </div>
                <div class="form-group">
                    <label>M-Pesa Transaction Code</label>
                    <input type="text" name="mpesa_reference" placeholder="e.g. QKH8923KLM" required class="form-control">
                </div>
                <button type="submit" name="process_payment" class="btn btn-primary" style="width: 100%;">Confirm Paybill Transaction</button>
            </form>
        </div>
    <?php endif; ?>
</div>
<?php require_once 'includes/footer.php'; ?>