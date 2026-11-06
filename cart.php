<?php
require_once 'config/security.php';
require_once 'config/database.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $pid = (int)$_POST['product_id'];
        $_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + 1;
    }
}

$cartItems = [];
$total = 0;
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
    $products = $stmt->fetchAll();
    foreach ($products as $p) {
        $qty = $_SESSION['cart'][$p['id']];
        $subtotal = $p['price'] * $qty;
        $total += $subtotal;
        $cartItems[] = ['product' => $p, 'qty' => $qty, 'subtotal' => $subtotal];
    }
}

require_once 'includes/header.php';
?>
<div class="header-container" style="padding: 40px 20px;">
    <h1>Your Shopping Cart</h1>
    <?php if (empty($cartItems)): ?>
        <p style="margin-top: 20px; color: #6b7280;">Your cart is currently empty. <a href="shop.php">Browse nursery shop</a></p>
    <?php else: ?>
        <table class="table">
            <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
            <tbody>
                <?php foreach($cartItems as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product']['name']); ?></td>
                        <td>KSh <?php echo number_format($item['product']['price'], 2); ?></td>
                        <td><?php echo $item['qty']; ?></td>
                        <td>KSh <?php echo number_format($item['subtotal'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px;">
            <h3>Subtotal: <span style="color: #2e7d32;">KSh <?php echo number_format($total, 2); ?></span></h3>
            <a href="checkout.php" class="btn btn-primary" style="font-size: 16px;">Checkout with M-Pesa &rarr;</a>
        </div>
    <?php endif; ?>
</div>
<?php require_once 'includes/footer.php'; ?>
