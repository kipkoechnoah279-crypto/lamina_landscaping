<?php
require_once 'config/security.php';
require_once 'config/database.php';

$cat = sanitize_input($_GET['category'] ?? '');
if ($cat) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ?");
    $stmt->execute([$cat]);
} else {
    $stmt = $pdo->query("SELECT * FROM products");
}
$products = $stmt->fetchAll();

require_once 'includes/header.php';
?>
<div class="header-container" style="padding: 40px 20px;">
    <h1>Explore Our Plant & Turf Nursery</h1>
    <div style="display: flex; gap: 30px; margin-top: 30px;">
        <div style="width: 220px; flex-shrink: 0;">
            <h3 style="border-bottom: 2px solid #558b2f; padding-bottom: 8px;">Categories</h3>
            <ul style="list-style: none; margin-top: 15px; line-height: 2.2;">
                <li><a href="shop.php" style="color: #333; text-decoration: none;">All Products</a></li>
                <li><a href="shop.php?category=turf" style="color: #333; text-decoration: none;">Turf & Grass</a></li>
                <li><a href="shop.php?category=plants" style="color: #333; text-decoration: none;">Plants & Flowers</a></li>
                <li><a href="shop.php?category=pots" style="color: #333; text-decoration: none;">Planters</a></li>
            </ul>
        </div>
        <div style="flex-grow: 1;" class="grid-3">
            <?php foreach($products as $p): ?>
                <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <?php if (!empty($p['image_url'])): ?>
                            <div style="height: 140px; border-radius: 8px; margin-bottom: 15px; overflow: hidden;">
                                <img src="<?php echo htmlspecialchars($p['image_url']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        <?php else: ?>
                            <div style="height: 140px; background: #e5e7eb; border-radius: 8px; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #4b5563;">
                                [Nursery Item]
                            </div>
                        <?php endif; ?>
                        
                        <h4 style="font-size: 18px; margin-bottom: 5px;"><?php echo htmlspecialchars($p['name']); ?></h4>
                        
                        <!-- Display Description -->
                        <p style="color: #6b7280; font-size: 13px; line-height: 1.4; margin-bottom: 15px;">
                            <?php echo htmlspecialchars($p['description'] ?? 'High-quality nursery product tailored for your landscaping needs.'); ?>
                        </p>

                        <p style="color: #2e7d32; font-weight: 900; font-size: 18px; margin-top: 5px;">
                            KSh <?php echo number_format($p['price'], 2); ?> <span style="font-size: 12px; color: #6b7280;">/<?php echo htmlspecialchars($p['unit']); ?></span>
                        </p>
                    </div>

                    <form method="POST" action="cart.php" style="margin-top: 15px;">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                        <button type="submit" name="add_to_cart" class="btn btn-primary" style="width: 100%;">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>