<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}
$contact_success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    $contact_success = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/logo.ico">
    <title>Contact Us — Anime Streaming</title>
    <link rel="stylesheet" href="../css/shared_styles.css">
    <link rel="stylesheet" href="../css/pages/contact.css">
</head>
<body>
    <?php $current_page = 'contact.php';
include '../includes/header_shared.php'; ?>

    <div class="contact-hero reveal">
        <h1>Contact Us</h1>
        <p>Have a question, suggestion, or just want to say hello? We'd love to hear from you!</p>
    </div>

    <div class="contact-wrapper">
        <div class="contact-grid">
            <div class="contact-form-card reveal-left">
                <h2>Send a Message</h2>
                <?php if ($contact_success): ?>
                    <div style="padding:12px;border-radius:10px;background:rgba(46,204,64,0.15);color:#4ade80;border:1px solid rgba(46,204,64,0.3);margin-bottom:16px;font-weight:500;">
                        Thank you! Your message has been sent successfully.
                    </div>
                <?php
endif; ?>
                <form class="contact-form" method="post" action="">
                    <input type="text" name="name" placeholder="Your Name" required>
                    <input type="email" name="email" placeholder="Your Email" required>
                    <input type="text" name="subject" placeholder="Subject" required>
                    <textarea name="message" placeholder="Your Message..." required></textarea>
                    <button type="submit" name="contact_submit">Send Message</button>
                </form>
            </div>

            <div class="contact-info-card reveal-right">
                <h2>Get in Touch</h2>
                <div class="info-item">
                    <div class="info-icon">📧</div>
                    <div>
                        <h3>Email</h3>
                        <p>support@animestreaming.com</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">📍</div>
                    <div>
                        <h3>Location</h3>
                        <p>Mumbai, India</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">⏰</div>
                    <div>
                        <h3>Working Hours</h3>
                        <p>Mon - Fri: 9AM - 6PM IST</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">💬</div>
                    <div>
                        <h3>Community</h3>
                        <p>Join our Discord for live support and discussions</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer_shared.php'; ?>
</body>
</html>
