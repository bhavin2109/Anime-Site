<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/logo.ico">
    <title>About Us — Anime Streaming</title>
    <link rel="stylesheet" href="../css/shared_styles.css">
    <link rel="stylesheet" href="../css/pages/about.css">
</head>
<body>
    <?php $current_page = 'about.php';
include '../includes/header_shared.php'; ?>

    <div class="about-hero reveal">
        <h1>About Us</h1>
        <p>We're a passionate team of anime enthusiasts building the ultimate streaming destination for fans worldwide.</p>
    </div>

    <section class="about-section">
        <div class="mission-block reveal">
            <h2>Our Mission</h2>
            <p>We believe everyone deserves access to the best anime content in a seamless and beautiful experience. 
            Our platform is built with love by anime fans, for anime fans — bringing together a vast library of series, movies, 
            and exclusive content all in one place. From classics to the latest releases, we've got you covered.</p>
        </div>

        <h2 class="reveal">What We Offer</h2>
        <div class="features-grid stagger-children">
            <div class="feature-card">
                <span class="feature-icon">🎬</span>
                <h3>Vast Library</h3>
                <p>Access thousands of anime titles across every genre — from action and adventure to romance and slice of life.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon">📱</span>
                <h3>Watch Anywhere</h3>
                <p>Enjoy your favorite anime on any device — desktop, tablet, or mobile with our responsive design.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon">📋</span>
                <h3>Personal Watchlist</h3>
                <p>Keep track of what you're watching, plan to watch, and have completed with our smart watchlist feature.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon">🔍</span>
                <h3>Smart Search</h3>
                <p>Find any anime instantly with our powerful search engine. Browse by genre, type, or just search by name.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon">🕐</span>
                <h3>Continue Watching</h3>
                <p>Pick up right where you left off. We keep track of your watch history so you never lose your place.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon">🎨</span>
                <h3>Beautiful Design</h3>
                <p>Enjoy a modern, immersive interface crafted with stunning animations and a premium dark theme.</p>
            </div>
        </div>

        <h2 class="reveal">Our Team</h2>
        <div class="team-grid stagger-children">
            <div class="team-card">
                <div class="team-avatar">B</div>
                <h3>Bhavin</h3>
                <p>Lead Developer</p>
            </div>
            <div class="team-card">
                <div class="team-avatar">A</div>
                <h3>Team Member</h3>
                <p>Backend Developer</p>
            </div>
            <div class="team-card">
                <div class="team-avatar">C</div>
                <h3>Team Member</h3>
                <p>UI/UX Designer</p>
            </div>
            <div class="team-card">
                <div class="team-avatar">D</div>
                <h3>Team Member</h3>
                <p>Database Engineer</p>
            </div>
        </div>
    </section>

    <?php include '../includes/footer_shared.php'; ?>
</body>
</html>
