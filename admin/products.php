<?php
require_once 'auth_admin.php';
require_once '../config/database.php';

$msg = '';
$error = '';

// Handle Product Deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    if (verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $product_id = (int)$_POST['product_id'];
        
        // Fetch image to delete file from server if stored locally
        $stmtImg = $pdo->prepare("SELECT image FROM products WHERE id = ?");
        $stmtImg->execute([$product_id]);
        $prod = $stmtImg->fetch();
        if ($prod && !empty($prod['image']) && strpos($prod['image'], 'http') !== 0) {
            $filePath = '../' . $prod['image'];
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        if ($stmt->execute([$product_id])) {
            $msg = "Product deleted successfully!";
        } else {
            $error = "Failed to delete product.";
        }
    }
}

// Handle Product Creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    if (verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $name = sanitize_input($_POST['name']);
        $category = sanitize_input($_POST['category']);
        $price = (float)$_POST['price'];
        $unit = sanitize_input($_POST['unit']);
        $stock = (int)$_POST['stock'];
        $description = sanitize_input($_POST['description'] ?? '');
        $image = sanitize_input($_POST['image_url'] ?? '');

        // Handle File Upload if selected
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image_file']['tmp_name'];
            $fileName = $_FILES['image_file']['name'];
            $uploadFileDir = '../assets/img/';
            
            if (!is_dir($uploadFileDir)) {
                @mkdir($uploadFileDir, 0755, true);
            }
            
            if (is_writable($uploadFileDir)) {
                $newFileName = md5(time() . $fileName) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $image = 'assets/img/' . $newFileName;
                } else {
                    $error = "Error: Failed to move uploaded file.";
                }
            } else {
                $error = "Error: Upload directory is not writable. Please use an Image URL instead.";
            }
        }

        // Only proceed with database insertion if no upload errors occurred
        if (empty($error)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO products (name, category, price, unit, stock, description, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $category, $price, $unit, $stock, $description, $image]);
                $msg = "Product added successfully!";
            } catch (\PDOException $e) {
                $error = "Database Error: " . $e->getMessage();
            }
        }
    }
}

$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
require_once '../includes/header.php';
?>
<div class="header-container" style="padding: 40px 20px; overflow-x: auto; width: 100%; margin-bottom: 20px;">
    <h1>Nursery Product Inventory</h1>
    <?php if ($msg): ?><div class="alert alert-success"><?php echo $msg; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

    <div class="card" style="margin: 20px 0;">
        <h3>Add New Nursery Item with Details</h3>
        <form method="POST" enctype="multipart/form-data" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="text" name="name" placeholder="Item Name" required class="form-control">
            <select name="category" class="form-control">
                <option value="turf">Turf</option>
                <option value="plants">Plants</option>
                <option value="pots">Planters</option>
            </select>
            <input type="number" step="0.01" name="price" placeholder="Price (KSh)" required class="form-control">
            <input type="text" name="unit" placeholder="Unit (e.g. sqm, pot)" required class="form-control">
            <input type="number" name="stock" placeholder="Stock Quantity" required class="form-control">
            <input type="text" name="image_url" placeholder="Or Image URL (https://...)" class="form-control">
            
            <div style="grid-column: span 2;">
                <label style="font-size: 13px; font-weight: bold; display: block; margin-bottom: 5px;">Product Description & Care Instructions</label>
                <textarea name="description" rows="3" placeholder="Enter plant care tips, dimensions, or features..." class="form-control"></textarea>
            </div>

            <div style="grid-column: span 2;">
                <label style="font-size: 13px; font-weight: bold; display: block; margin-bottom: 5px;">Upload Image File (Optional)</label>
                <input type="file" name="image_file" accept="image/*" class="form-control">
            </div>

            <button type="submit" name="add_product" class="btn btn-primary" style="grid-column: span 2;">Save Item</button>
        </form>
    </div>

    <table class="table" >
        <thead>
            <tr><th>Image</th><th>Name & Details</th><th>Category</th><th>Price</th><th>Unit</th><th>Stock</th><th>Action</th></tr>
        </thead>
        <tbody>
            <?php foreach($products as $p): ?>
                <tr>
                    <td>
                        <?php if (!empty($p['image'])): ?>
                            <img src="<?php echo (strpos($p['image'], 'http') === 0) ? $p['image'] : '../' . $p['image']; ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                        <?php else: ?>
                            <div style="width: 50px; height: 50px; background: #e5e7eb; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #666;">No Img</div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($p['name']); ?></strong>
                        <p style="font-size: 12px; color: #6b7280; margin-top: 2px; max-width: 250px;"><?php echo htmlspecialchars($p['description'] ?? ''); ?></p>
                    </td>
                    <td><?php echo htmlspecialchars($p['category']); ?></td>
                    <td>KSh <?php echo number_format($p['price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($p['unit']); ?></td>
                    <td><?php echo $p['stock']; ?></td>
                    <td>
                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                            <button type="submit" name="delete_product" class="btn" style="background-color: #d32f2f; color: white; padding: 6px 12px; font-size: 12px;">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once '../includes/footer.php'; ?>