<?php
require_once 'config/security.php';
require_once 'includes/header.php';
?>
<div class="header-container" style="padding: 40px 20px; text-align: center;">
    <span style="color: #558b2f; font-weight: bold; text-transform: uppercase; font-size: 13px;">Maintenance Subscriptions</span>
    <h1 style="margin-top: 5px;">Flexible Lawn & Garden Plans</h1>
    <p style="color: #6b7280; margin-bottom: 40px;">Select a recurring plan for hassle-free groundskeeping. Pay effortlessly via M-Pesa.</p>

    <div class="grid-3">
        <div class="card">
            <h3>Basic Lawn Care</h3>
            <p style="font-size: 32px; font-weight: 900; color: #2e7d32; margin: 15px 0;">KSh 12,500 <span style="font-size: 12px; color: #6b7280;">/ mo</span></p>
            <ul style="list-style: none; text-align: left; line-height: 2; margin-bottom: 20px; color: #4b5563;">
                <li>- Bi-weekly lawn mowing</li>
                <li>- Edge trimming & driveway sweep</li>
                <li>- Seasonal fertilizer treatment</li>
            </ul>
            <a href="checkout.php" class="btn btn-primary" style="width: 100%;">Subscribe with M-Pesa</a>
        </div>

        <div class="card card-featured">
            <span class="badge-popular">Most Popular</span>
            <h3>Premium Estate Maintenance</h3>
            <p style="font-size: 32px; font-weight: 900; color: #2e7d32; margin: 15px 0;">KSh 28,000 <span style="font-size: 12px; color: #6b7280;">/ mo</span></p>
            <ul style="list-style: none; text-align: left; line-height: 2; margin-bottom: 20px; color: #4b5563;">
                <li>- Weekly mowing & precision edging</li>
                <li>- Shrub & hedge pruning</li>
                <li>- Automated drip/sprinkler checks</li>
                <li>- Free eco-pest control spray</li>
            </ul>
            <a href="checkout.php" class="btn btn-primary" style="width: 100%;">Subscribe with M-Pesa</a>
        </div>

        <div class="card">
            <h3>Commercial Grounds</h3>
            <p style="font-size: 32px; font-weight: 900; color: #2e7d32; margin: 15px 0;">KSh 65,000 <span style="font-size: 12px; color: #6b7280;">/ mo</span></p>
            <ul style="list-style: none; text-align: left; line-height: 2; margin-bottom: 20px; color: #4b5563;">
                <li>- Dedicated grounds team</li>
                <li>- Hardscape & flower bed refresh</li>
                <li>- Smart water management</li>
            </ul>
            <a href="checkout.php" class="btn btn-primary" style="width: 100%;">Subscribe with M-Pesa</a>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
