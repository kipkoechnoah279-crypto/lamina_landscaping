<?php
require_once 'config/security.php';
require_once 'config/database.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $fname = sanitize_input($_POST['first_name']);
        $lname = sanitize_input($_POST['last_name']);
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $phone = sanitize_input($_POST['phone']);
        $pass = $_POST['password'];

        if (strlen($pass) < 6) {
            $error = "Password must be at least 6 characters.";
        } else {
            $hash = password_hash($pass, PASSWORD_BCRYPT);
            try {
                $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, password, role) VALUES (?, ?, ?, ?, ?, 'customer')");
                $stmt->execute([$fname, $lname, $email, $phone, $hash]);
                header("Location: login.php?registered=1");
                exit();
            } catch (\PDOException $e) {
                $error = "Email address already registered.";
            }
        }
    }
}
require_once 'includes/header.php';
?>
<div class="header-container" style="padding: 60px 20px; max-width: 480px;">
    <div class="card">
        <h2 style="text-align: center; margin-bottom: 20px;">Create Account</h2>
        <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="form-group"><label>First Name</label><input type="text" name="first_name" required class="form-control"></div>
            <div class="form-group"><label>Last Name</label><input type="text" name="last_name" required class="form-control"></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" required class="form-control"></div>
            <div class="form-group"><label>Phone Number</label><input type="text" name="phone" required class="form-control"></div>
            <div class="form-group"><label>Password</label><input type="password" name="password" required class="form-control"></div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Create Account</button>
        </form>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
