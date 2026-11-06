   
    <?php
// Reliable check if running from inside the admin folder
$in_admin_footer = strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false;
$js_prefix = $in_admin_footer ? '../assets/' : 'assets/';
$footer_root = $in_admin_footer ? '../' : '';
?>
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-grid">
                
                <div class="footer-col">
                    <div class="logo-link" style="margin-bottom: 15px;">
                        <div class="logo-icon" style="width: 32px; height: 32px; font-size: 16px;">L</div>
                        <span class="logo-title" style="color: white;">Lamina Landscaping</span>
                    </div>
                    <p style="color: #9ca3af; font-size: 14px; line-height: 1.6;">
                        Transforming landscapes across the Country into vibrant eco-friendly spaces.
                    </p>
                </div>

                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="<?php echo $root_prefix; ?>index.php#home">Home</a></li>
                         <a href="<?php echo $root_prefix; ?>index.php#services">Services</a></li>
                        <li> <a href="<?php echo $root_prefix; ?>index.php#about">About</a></li>
                        <li><a href="<?php echo $root_prefix; ?>shop.php">Garden Nursery</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Contact Details</h4>
                    <ul>
                        
                        <li>Phone: +254 712 345 678</li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Working Hours</h4>
                    <p style="color: #9ca3af; font-size: 14px;">Mon - Fri: 8:00 AM - 6:00 PM</p>
                    <p style="color: #9ca3af; font-size: 14px;">Saturday: 9:00 AM - 4:00 PM</p>
                    <p style="color: #9ca3af; font-size: 14px;">Sunday: Closed</p>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2026 Lamina Landscaping. All rights reserved.</p>
                <p>designed by <strong style="color: #689f38;">NOBEATECH</strong></p>
            </div>
        </div>
    </footer>

    <script src="<?php echo $js_prefix; ?>js/main.js"></script>
</body>
</html>