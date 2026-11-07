<?php
require_once 'auth_admin.php';
require_once '../config/database.php';

$msg = '';
$error = '';

// 1. HANDLE USER DELETION
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    if (verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $user_id = (int)$_POST['user_id'];
        
        // Prevent admin from deleting themselves
        if ($user_id === (int)$_SESSION['user_id']) {
            $error = "You cannot delete your own logged-in admin account!";
        } else {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            if ($stmt->execute([$user_id])) {
                $msg = "User deleted successfully!";
            } else {
                $error = "Failed to delete user.";
            }
        }
    }
}

// 2. HANDLE ADD USER MANUALLY
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    if (verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $fname = sanitize_input($_POST['first_name']);
        $lname = sanitize_input($_POST['last_name']);
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $phone = sanitize_input($_POST['phone']);
        $role  = sanitize_input($_POST['role']);
        $pass  = $_POST['password'];

        if (strlen($pass) < 6) {
            $error = "Password must be at least 6 characters long.";
        } else {
            $hash = password_hash($pass, PASSWORD_BCRYPT);
            try {
                $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, password, role) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$fname, $lname, $email, $phone, $hash, $role]);
                $msg = "User created successfully!";
            } catch (\PDOException $e) {
                $error = "Email address already registered.";
            }
        }
    }
}

// 3. HANDLE UPDATE USER DETAILS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    if (verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $user_id = (int)$_POST['user_id'];
        $fname   = sanitize_input($_POST['first_name']);
        $lname   = sanitize_input($_POST['last_name']);
        $email   = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $phone   = sanitize_input($_POST['phone']);
        $role    = sanitize_input($_POST['role']);
        $pass    = $_POST['password'] ?? '';

        try {
            if (!empty($pass)) {
                $hash = password_hash($pass, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, role = ?, password = ? WHERE id = ?");
                $stmt->execute([$fname, $lname, $email, $phone, $role, $hash, $user_id]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, role = ? WHERE id = ?");
                $stmt->execute([$fname, $lname, $email, $phone, $role, $user_id]);
            }
            $msg = "User details updated successfully!";
        } catch (\PDOException $e) {
            $error = "Failed to update user. Email might be in use.";
        }
    }
}

// FETCH ALL USERS FROM DATABASE
$users = $pdo->query("SELECT id, first_name, last_name, email, phone, role, created_at FROM users ORDER BY id DESC")->fetchAll();

require_once '../includes/header.php';
?>

<div class="header-container" style="padding: 40px 20px;">
    <h1>User Account Management</h1>
    <p style="color: #6b7280; margin-bottom: 25px;">View, create, update, and remove registered users from the system.</p>

    <!-- Navigation Bar -->
    <div style="margin-bottom: 20px; display: flex; gap: 15px; flex-wrap: wrap;">
        <a href="dashboard.php" class="btn" style="background: #e5e7eb; color: #333; text-decoration:none;">Dashboard</a>
        <a href="users.php" class="btn btn-primary" style="text-decoration:none;">Manage Users</a>
        <a href="products.php" class="btn" style="background: #e5e7eb; color: #333; text-decoration:none;">Manage Nursery</a>
        <a href="orders.php" class="btn" style="background: #e5e7eb; color: #333; text-decoration:none;">Orders & Payments</a>
        <a href="consultations.php" class="btn" style="background: #e5e7eb; color: #333; text-decoration:none;">Consultations</a>
    </div>

    <!-- Alert Notifications -->
    <?php if ($msg): ?><div class="alert alert-success" style="background:#dcfce7; color:#15803d; padding:12px; border-radius:6px; margin-bottom:20px;"><?php echo $msg; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger" style="background:#fee2e2; color:#b91c1c; padding:12px; border-radius:6px; margin-bottom:20px;"><?php echo $error; ?></div><?php endif; ?>

    <!-- ADD USER FORM -->
    <div class="card" style="margin-bottom: 30px; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <h3 style="margin-bottom: 15px;">Add New User Manually</h3>
        <form method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div>
                <label style="font-size:12px; font-weight:bold;">First Name *</label>
                <input type="text" name="first_name" required class="form-control" style="width:100%; padding:8px; margin-top:4px;">
            </div>
            <div>
                <label style="font-size:12px; font-weight:bold;">Last Name *</label>
                <input type="text" name="last_name" required class="form-control" style="width:100%; padding:8px; margin-top:4px;">
            </div>
            <div>
                <label style="font-size:12px; font-weight:bold;">Email Address *</label>
                <input type="email" name="email" required class="form-control" style="width:100%; padding:8px; margin-top:4px;">
            </div>
            <div>
                <label style="font-size:12px; font-weight:bold;">Phone Number *</label>
                <input type="text" name="phone" required class="form-control" style="width:100%; padding:8px; margin-top:4px;">
            </div>
            <div>
                <label style="font-size:12px; font-weight:bold;">Password *</label>
                <input type="password" name="password" required class="form-control" style="width:100%; padding:8px; margin-top:4px;">
            </div>
            <div>
                <label style="font-size:12px; font-weight:bold;">Account Role</label>
                <select name="role" class="form-control" style="width:100%; padding:8px; margin-top:4px;">
                    <option value="customer">Customer</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>
            <div style="grid-column: 1 / -1;">
                <button type="submit" name="add_user" class="btn btn-primary" style="padding: 10px 20px;">+ Create User</button>
            </div>
        </form>
    </div>

    <!-- USERS TABLE -->
    <div style="overflow-x: auto;">
        <table class="table" style="width: 100%; border-collapse: collapse; background: white;">
            <thead>
                <tr style="background: #f3f4f6; text-align: left;">
                    <th style="padding: 12px;">ID</th>
                    <th style="padding: 12px;">Name</th>
                    <th style="padding: 12px;">Email</th>
                    <th style="padding: 12px;">Phone</th>
                    <th style="padding: 12px;">Role</th>
                    <th style="padding: 12px;">Registered Date</th>
                    <th style="padding: 12px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px;"><strong>#<?php echo $u['id']; ?></strong></td>
                        <td style="padding: 12px;"><?php echo htmlspecialchars($u['first_name'] . ' ' . $u['last_name']); ?></td>
                        <td style="padding: 12px;"><?php echo htmlspecialchars($u['email']); ?></td>
                        <td style="padding: 12px;"><?php echo htmlspecialchars($u['phone']); ?></td>
                        <td style="padding: 12px;">
                            <span style="padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; <?php echo $u['role'] === 'admin' ? 'background: #fef3c7; color: #92400e;' : 'background: #e0f2fe; color: #0369a1;'; ?>">
                                <?php echo ucfirst($u['role']); ?>
                            </span>
                        </td>
                        <td style="padding: 12px; font-size: 13px; color: #6b7280;"><?php echo date('M d, Y', strtotime($u['created_at'])); ?></td>
                        <td style="padding: 12px; text-align: center;">
                            <!-- UPDATE USER TRIGGER FORM -->
                            <details style="display:inline-block; text-align:left;">
                                <summary class="btn" style="padding:4px 8px; font-size:12px; background:#e5e7eb; cursor:pointer;">Edit</summary>
                                <div style="position:absolute; right:20px; background:white; border:1px solid #ccc; padding:20px; box-shadow:0 4px 12px rgba(0,0,0,0.15); z-index:100; width:300px; border-radius:8px;">
                                    <h4 style="margin-bottom:10px;">Edit User #<?php echo $u['id']; ?></h4>
                                    <form method="POST">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                        <label style="font-size:11px;">First Name</label>
                                        <input type="text" name="first_name" value="<?php echo htmlspecialchars($u['first_name']); ?>" required class="form-control" style="width:100%; padding:5px; margin-bottom:8px;">
                                        <label style="font-size:11px;">Last Name</label>
                                        <input type="text" name="last_name" value="<?php echo htmlspecialchars($u['last_name']); ?>" required class="form-control" style="width:100%; padding:5px; margin-bottom:8px;">
                                        <label style="font-size:11px;">Email</label>
                                        <input type="email" name="email" value="<?php echo htmlspecialchars($u['email']); ?>" required class="form-control" style="width:100%; padding:5px; margin-bottom:8px;">
                                        <label style="font-size:11px;">Phone</label>
                                        <input type="text" name="phone" value="<?php echo htmlspecialchars($u['phone']); ?>" required class="form-control" style="width:100%; padding:5px; margin-bottom:8px;">
                                        <label style="font-size:11px;">Role</label>
                                        <select name="role" class="form-control" style="width:100%; padding:5px; margin-bottom:8px;">
                                            <option value="customer" <?php echo $u['role'] === 'customer' ? 'selected' : ''; ?>>Customer</option>
                                            <option value="admin" <?php echo $u['role'] === 'admin' ? 'selected' : ''; ?>>Administrator</option>
                                        </select>
                                        <label style="font-size:11px;">New Password (leave blank to keep current)</label>
                                        <input type="password" name="password" placeholder="Optional" class="form-control" style="width:100%; padding:5px; margin-bottom:12px;">
                                        
                                        <button type="submit" name="update_user" class="btn btn-primary" style="width:100%; padding:6px;">Save Changes</button>
                                    </form>
                                </div>
                            </details>

                            <!-- DELETE USER FORM -->
                            <form method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to permanently delete this user from the database?');">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                <button type="submit" name="delete_user" class="btn" style="padding:4px 8px; font-size:12px; background:#ef4444; color:white; border:none; border-radius:4px; cursor:pointer;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>