<?php
require_once 'auth_admin.php';
require_once '../config/database.php';

// Handle Status Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_consultation_status'])) {
    if (verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $consultation_id = (int)$_POST['consultation_id'];
        $new_status = sanitize_input($_POST['status']);
        
        $stmt = $pdo->prepare("UPDATE consultations SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $consultation_id]);
    }
}

// Fetch consultations with a safe LEFT JOIN in case user_id is missing or null
$consultations = $pdo->query("
    SELECT c.*, 
           COALESCE(u.first_name, 'Guest') as first_name, 
           COALESCE(u.last_name, '') as last_name, 
           COALESCE(u.email, c.email, 'N/A') as email, 
           COALESCE(u.phone, c.phone, 'N/A') as phone 
    FROM consultations c 
    LEFT JOIN users u ON c.user_id = u.id 
    ORDER BY c.id DESC
")->fetchAll();

require_once '../includes/header.php';
?>
<div class="header-container" style="padding: 40px 20px;">
    <h1>Manage Consultation Requests</h1>
    <p style="color: #6b7280; margin-bottom: 25px;">Review customer site visits, landscape consultations, and update booking statuses.</p>

    <div style="margin-bottom: 20px; display: flex; gap: 15px;">
        <a href="dashboard.php" class="btn" style="background: #e5e7eb; color: #333; text-decoration:none; padding:8px 12px; border-radius:4px;">Dashboard</a>
        <a href="products.php" class="btn" style="background: #e5e7eb; color: #333; text-decoration:none; padding:8px 12px; border-radius:4px;">Manage Nursery</a>
        <a href="orders.php" class="btn" style="background: #e5e7eb; color: #333; text-decoration:none; padding:8px 12px; border-radius:4px;">Orders & Payments</a>
        <a href="consultations.php" class="btn" style="background: #558b2f; color: white; text-decoration:none; padding:8px 12px; border-radius:4px;">Consultations</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Contact</th>
                <th>Preferred Date</th>
                <th>Notes / Details</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($consultations)): ?>
                <tr><td colspan="7" style="text-align: center; color: #6b7280; padding: 20px;">No consultation requests found.</td></tr>
            <?php else: ?>
                <?php foreach($consultations as $c): ?>
                    <tr>
                        <td><strong>#<?php echo $c['id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($c['first_name'] . ' ' . $c['last_name']); ?></td>
                        <td>
                            <?php echo htmlspecialchars($c['phone']); ?><br>
                            <span style="font-size: 12px; color: #6b7280;"><?php echo htmlspecialchars($c['email']); ?></span>
                        </td>
                        <td><?php echo htmlspecialchars($c['preferred_date'] ?? 'As soon as possible'); ?></td>
                        <td><div style="max-width: 250px; font-size: 13px;"><?php echo htmlspecialchars($c['notes'] ?? 'Standard consultation request'); ?></div></td>
                        <td>
                            <span style="background: #e0f2fe; color: #0369a1; padding: 4px 8px; font-weight: bold; font-size: 12px; border-radius: 4px;">
                                <?php echo htmlspecialchars($c['status'] ?? 'Pending'); ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" style="display:flex; gap:5px;">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="consultation_id" value="<?php echo $c['id']; ?>">
                                <select name="status" class="form-control" style="padding:4px; font-size: 12px;">
                                    <option value="Pending" <?php if(($c['status']??'')=='Pending') echo 'selected'; ?>>Pending</option>
                                    <option value="Confirmed" <?php if(($c['status']??'')=='Confirmed') echo 'selected'; ?>>Confirmed</option>
                                    <option value="Completed" <?php if(($c['status']??'')=='Completed') echo 'selected'; ?>>Completed</option>
                                    <option value="Cancelled" <?php if(($c['status']??'')=='Cancelled') echo 'selected'; ?>>Cancelled</option>
                                </select>
                                <button type="submit" name="update_consultation_status" class="btn btn-primary" style="padding:4px 8px; font-size:12px;">Save</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require_once '../includes/footer.php'; ?>