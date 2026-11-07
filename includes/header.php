<?php
require_once __DIR__ . '/../config/security.php';

// Check if current script is running from inside the 'admin' directory
$in_admin = strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false;

// Set correct relative prefixes based on location
$asset_prefix = $in_admin ? '../assets/' : 'assets/';
$root_prefix  = $in_admin ? '../' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Lamina Landscaping | Premium Outdoor Solutions Kenya</title>
    <link rel="stylesheet" href="<?php echo $asset_prefix; ?>css/style.css">
</head>
<body>

    <div class="top-announcement-bar">
        <div class="header-container flex-container">
            <div class="flex-items-space">
                <span>Call Us Now: <a href="tel:+254712345678" style="color: white; font-weight: bold; text-decoration: underline;">+254 712 345 678</a></span>
                <span class="top-location">Nairobi & Central Kenya</span>
            </div>
        </div>
    </div>

    <header class="main-header">
        <div class="header-container flex-header">
            
            <a href="<?php echo $root_prefix; ?>index.php" class="logo-link">
                <div class="logo-icon">L</div>
                <div class="logo-text">
                    <span class="logo-title">Lamina</span>
                    <span class="logo-sub">Landscaping</span>
                </div>
            </a>

            <!-- Navigation Menu -->
            <nav class="nav-menu" id="navMenu">
                <a href="<?php echo $root_prefix; ?>index.php#home">Home</a>
                <a href="<?php echo $root_prefix; ?>index.php#services">Services</a>
                <a href="<?php echo $root_prefix; ?>index.php#about">About</a>
                <a href="<?php echo $root_prefix; ?>index.php#testimonials">Testimonials</a>
                <a href="<?php echo $root_prefix; ?>subscriptions.php">Subscriptions</a>
                <a href="<?php echo $root_prefix; ?>shop.php">Shop</a>
                <a href="<?php echo $root_prefix; ?>index.php#intro-call">Intro Call</a>

                <!-- Mobile-Only Actions Drawer Content -->
                <div class="mobile-nav-actions">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="<?php echo $root_prefix; ?>dashboard.php" class="user-btn">
                            <span>Account: <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        </a>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="<?php echo $root_prefix; ?>admin/dashboard.php" class="badge-admin">Admin Portal</a>
                        <?php endif; ?>
                        <a href="<?php echo $root_prefix; ?>logout.php" style="color: #c62828; font-size: 14px; font-weight: 600;">Log Out</a>
                    <?php else: ?>
                        <a href="<?php echo $root_prefix; ?>login.php" class="user-btn">Sign In</a>
                        <a href="<?php echo $root_prefix; ?>register.php" class="btn btn-primary" style="text-align: center; width: 100%;">Create Account</a>
                    <?php endif; ?>
                    <a href="<?php echo $root_prefix; ?>cart.php" class="cart-btn" style="justify-content: center;">
                        <span>Cart</span>
                        <span class="cart-badge"><?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?></span>
                    </a>
                </div>
            </nav>

            <!-- Desktop Actions Header -->
            <div class="nav-actions desktop-actions">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo $root_prefix; ?>dashboard.php" class="user-btn">
                        <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    </a>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="<?php echo $root_prefix; ?>admin/dashboard.php" class="badge-admin">Admin Portal</a>
                    <?php endif; ?>
                    <a href="<?php echo $root_prefix; ?>logout.php" style="color: #c62828; font-size: 13px; font-weight: 600;">Log Out</a>
                <?php else: ?>
                    <a href="<?php echo $root_prefix; ?>login.php" class="user-btn">Sign In</a>
                    <a href="<?php echo $root_prefix; ?>register.php" class="btn btn-primary" style="padding: 6px 14px; font-size: 13px;">Create Account</a>
                <?php endif; ?>

                <a href="<?php echo $root_prefix; ?>cart.php" class="cart-btn">
                    <span>Cart</span>
                    <span class="cart-badge"><?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?></span>
                </a>
            </div>

            <!-- Hamburger Menu Toggle Button (Visible on mobile only) -->
            <button class="hamburger-btn" id="hamburgerToggle" aria-label="Toggle Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>