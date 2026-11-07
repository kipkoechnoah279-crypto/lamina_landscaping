<?php
require_once 'config/security.php';
require_once 'config/database.php';

$error = '';
$success = '';

// Check if token or email is provided via GET or POST
$token = $_GET['token'] ?? $_POST['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if (empty($email)) {
            $error = "Please enter your email address.";
        } elseif (strlen($password) < 6) {
            $error = "Password must be at least 6 characters.";
        } elseif ($password !== $confirm_password) {
            $error = "Passwords do not match.";
        } else {
            // Verify if user exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                // Hash new password securely with bcrypt
                $new_hash = password_hash($password, PASSWORD_BCRYPT);

                // Update user password in database
                $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
                $updateStmt->execute([$new_hash, $email]);

                $success = "Password has been successfully updated. You can now <a href='login.php'>sign in</a>.";
            } else {
                $error = "No account found with that email address.";
            }
        }
    } else {
        $error = "Invalid CSRF token. Please try again.";
    }
}

require_once 'includes/header.php';
?>
<div class="header-container" style="padding: 60px 20px; max-width: 450px;">
    <div class="card">
        <h2 style="text-align: center; margin-bottom: 20px;">Reset Password</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php else: ?>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="Enter your account email" required class="form-control">
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label>New Password</label>
                    <input type="password" name="password" placeholder="Enter new password (min 6 chars)" required class="form-control">
                </div>

                <div class="form-group" style="margin-bottom: 25px;">
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_password" placeholder="Confirm new password" required class="form-control">
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Update Password</button>
            </form>
        <?php endif; ?>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>