<?php
$is_in_pages_f = (strpos($_SERVER['PHP_SELF'], '/pages/') !== false);
$is_in_includes_f = (strpos($_SERVER['PHP_SELF'], '/includes/') !== false);
$is_in_sub_f = ($is_in_pages_f || $is_in_includes_f);

$footer_about_path = $is_in_sub_f ? ($is_in_pages_f ? 'about.php' : '../pages/about.php') : './pages/about.php';
$footer_contact_path = $is_in_sub_f ? ($is_in_pages_f ? 'contact.php' : '../pages/contact.php') : './pages/contact.php';
$footer_explore_path = $is_in_sub_f ? ($is_in_pages_f ? 'explore.php' : '../pages/explore.php') : './pages/explore.php';
$footer_home_path = $is_in_sub_f ? '../home.php' : './home.php';
$footer_icons_path = $is_in_sub_f ? '../assets/icons/' : './assets/icons/';
?>
<footer>
    <div class="footer-content">
        <div class="footer-section">
            <h3>About Us</h3>
            <p>Your one-stop destination for streaming and discovering your favorite anime and movies!</p>
            <p>We provide anime fans with a seamless and enjoyable experience.</p>
        </div>
        <div class="footer-section">
            <h3>Quick Links</h3>
            <a href="<?php echo $footer_home_path; ?>">Home</a>
            <a href="<?php echo $footer_explore_path; ?>">Explore Movies</a>
            <a href="<?php echo $footer_about_path; ?>">About Us</a>
            <a href="<?php echo $footer_contact_path; ?>">Contact Us</a>
        </div>
        <div class="footer-section">
            <h3>Connect With Us</h3>
            <p>Built by Group No.2 — bringing the best anime content to fans worldwide.</p>
            <div class="footer-social">
                <a href="#" title="Facebook"><img src="<?php echo $footer_icons_path; ?>facebook.png" alt="Facebook"></a>
                <a href="#" title="Instagram"><img src="<?php echo $footer_icons_path; ?>instagram.png" alt="Instagram"></a>
                <a href="#" title="Twitter"><img src="<?php echo $footer_icons_path; ?>twitter.png" alt="Twitter"></a>
                <a href="#" title="Telegram"><img src="<?php echo $footer_icons_path; ?>telegram.png" alt="Telegram"></a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> Anime Streaming Site &mdash; Group No.2</p>
    </div>
</footer>
