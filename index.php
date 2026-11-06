<?php
require_once 'config/security.php';
require_once 'config/database.php';

$consult_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_consultation'])) {
    if (verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $fname = sanitize_input($_POST['first_name']);
        $lname = sanitize_input($_POST['last_name']);
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $phone = sanitize_input($_POST['phone']);
        $details = sanitize_input($_POST['project_details']);
        $rating = (int)($_POST['rating'] ?? 5);

        $stmt = $pdo->prepare("INSERT INTO consultations (first_name, last_name, email, phone, project_details, rating) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$fname, $lname, $email, $phone, $details, $rating]);
        $consult_msg = "Thank you! Your consultation request has been received.";
    }
}
require_once 'includes/header.php';
?>
<!-- HERO BANNER SECTION -->
<section id="home" class="hero-banner">
    <div class="header-container">
        <div class="hero-grid">
            <div>
                <span style="background: rgba(255,255,255,0.1); padding: 6px 14px; border-radius: 9999px; font-size: 13px; font-weight: bold; color: #a7f3d0;">
                    Kenya's #1 Rated Lawn & Landscape Experts
                </span>
                <h1 class="hero-title">The Best Landscapers in <span style="color: #689f38;">Kenya</span></h1>
                <p class="hero-sub">Transform your outdoor spaces into thriving, sustainable retreats. Precision lawn care, drip irrigation, and custom landscape design.</p>
                <div style="display: flex; gap: 15px;">
                    <a href="#intro-call" class="btn btn-primary" style="padding: 14px 28px; font-size: 16px;">Schedule a Consultation &gt;</a>
                    <a href="subscriptions.php" class="btn" style="background: rgba(255,255,255,0.15); color: white;">View Maintenance Plans</a>
                </div>
            </div>

            <!-- Promotional Hero Banner Card -->
            <div class="glass-card">
                <span style="background: #f59e0b; color: white; font-weight: bold; font-size: 11px; padding: 3px 8px; border-radius: 9999px; text-transform: uppercase;">Limited Offer</span>
                <h3 style="margin: 10px 0; font-size: 20px;">Welcome Gift: 50% Off First Month Subscription</h3>
                <ul style="list-style: none; margin: 15px 0; font-size: 14px; line-height: 1.8; color: #4b5563;">
                    <li>- <strong>Free Lawn Diagnostic Assessment</strong> for first-time clients.</li>
                    <li>- Weekly scheduled mowing, edging, and weed management.</li>
                    <li>- Easy payments.</li>
                </ul>
                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e5e7eb; padding-top: 15px;">
                    <div>
                        <span style="font-size: 12px; color: #6b7280; display: block;">Starting at only</span>
                        <span style="font-size: 24px; font-weight: 900; color: #2e7d32;">KSh 12,500 <span style="font-size: 12px; color: #6b7280;">/mo</span></span>
                    </div>
                    <a href="subscriptions.php" class="btn btn-primary">Claim Offer</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ABOUT SECTION -->
<section id="about" class="about-section" style="padding: 80px 0; background: white;" >
    <div class="header-container">
        <div class="about-grid">
            <div>
                <img src="assets/img/path.jpg" alt="Landscaping" style="width: 100%; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
            </div>
            <div>
                <span style="color: #558b2f; font-weight: bold; text-transform: uppercase; font-size: 13px;">About Lamina Landscaping</span>
                <h2 style="font-size: 32px; margin: 10px 0;">Creating Sustainable Outdoor Spaces</h2>
                <p style="color: #4b5563; margin-bottom: 15px;">At Lamina Landscaping, we specialize in transforming your outdoor spaces into stunning, sustainable retreats. Our services include comprehensive lawn care, tree pruning, and smart irrigation.</p>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div style="background: #f4f8f3; padding: 15px; border-radius: 8px;">
                        <h4 style="color: #111827;">Eco-Friendly</h4>
                        <p style="font-size: 12px; color: #6b7280;">Native plant species & water saving drip focus.</p>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SERVICES SECTION -->
<section id="services" class="services-section" style="padding: 80px 0; background: #f8faf9;">
    <div class="header-container">
        <div style="text-align: center; max-width: 600px; margin: 0 auto 40px auto;">
            <span style="color: #558b2f; font-weight: bold; text-transform: uppercase; font-size: 13px;">What We Offer</span>
            <h2 style="font-size: 32px; margin-top: 5px;">Our Landscaping Services</h2>
        </div>
        <div class="grid-3">
            <div class="card">
                <span style="background: #2e7d32; color: white; font-weight: bold; padding: 2px 8px; border-radius: 9999px; font-size: 12px;">01</span>
                <h3 style="margin: 15px 0;">Lawn Care Service</h3>
                <p style="color: #6b7280; font-size: 14px;">Keep your property healthy with scheduled mowing, edging, trimming, and seasonal yard cleanups.</p>
            </div>
            <div class="card">
                <span style="background: #2e7d32; color: white; font-weight: bold; padding: 2px 8px; border-radius: 9999px; font-size: 12px;">02</span>
                <h3 style="margin: 15px 0;">Landscape Design</h3>
                <p style="color: #6b7280; font-size: 14px;">Transform an ordinary outdoor area into a modern, sustainable retreat with native plants and tailored features.</p>
            </div>
            <div class="card">
                <span style="background: #2e7d32; color: white; font-weight: bold; padding: 2px 8px; border-radius: 9999px; font-size: 12px;">03</span>
                <h3 style="margin: 15px 0;">Irrigation Installation</h3>
                <p style="color: #6b7280; font-size: 14px;">Improve landscape health and reduce water waste with professionally installed drip or sprinkler systems.</p>
            </div>
        </div>
    </div>
</section>

<!-- TESTIMONIALS SECTION -->
<section id="testimonials" style="padding: 80px 0; background: white; text-align: center;">
    <div class="header-container" style="max-width: 800px;">
        <span style="color: #558b2f; font-weight: bold; text-transform: uppercase; font-size: 13px;">Client Reviews</span>
        <h2 style="font-size: 32px; margin: 10px 0 30px 0;">What Our Clients Say</h2>
        <blockquote style="font-size: 20px; font-style: italic; color: #374151; line-height: 1.6;">
            "Lamina Landscaping transformed my backyard into a beautiful oasis. Their team was professional and attentive, and I couldn't be happier with the results!"
        </blockquote>
        <p style="margin-top: 15px; font-weight: bold; color: #111827;">Emily T.</p>
        <p style="font-size: 13px; color: #558b2f; font-weight: 600;">Homeowner in Karen, Nairobi</p>
    </div>
</section>

<!-- INTRO CALL / CONSULTATION FORM SECTION -->
<section id="intro-call" style="padding: 80px 0; background: #1b5e20; color: white;">
    <div class="header-container" style="max-width: 600px;">
        <h2 style="text-align: center; font-size: 32px; margin-bottom: 10px;">Tell us what you're interested in</h2>
        <p style="text-align: center; margin-bottom: 30px; font-weight: 300;">Book an Intro Call or request a site inspection quote</p>

        <?php if ($consult_msg): ?>
            <div style="background: white; color: #1b5e20; padding: 15px; border-radius: 8px; text-align: center; margin-bottom: 20px; font-weight: bold;"><?php echo $consult_msg; ?></div>
        <?php endif; ?>

        <form method="POST" action="#intro-call" style="display: flex; flex-direction: column; gap: 15px;">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <input type="text" name="first_name" placeholder="First Name *" required class="form-control">
                <input type="text" name="last_name" placeholder="Last Name *" required class="form-control">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <input type="email" name="email" placeholder="Email Address *" required class="form-control">
                <input type="text" name="phone" placeholder="Phone Number *" required class="form-control">
            </div>
            <textarea name="project_details" rows="4" placeholder="Short description of your project" required class="form-control"></textarea>
            
            <button type="submit" name="submit_consultation" class="btn" style="background: #689f38; color: white; padding: 15px; font-size: 16px; font-weight: bold;">Submit Request</button>
        </form>
    </div>
</section>
<?php require_once 'includes/footer.php'; ?>
