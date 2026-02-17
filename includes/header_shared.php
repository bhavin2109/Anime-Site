<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$profile_pic_url = '';
$username = '';
$is_in_pages_check = (strpos($_SERVER['PHP_SELF'], '/pages/') !== false);
$is_in_includes_check = (strpos($_SERVER['PHP_SELF'], '/includes/') !== false);
$profile_pics_path = ($is_in_pages_check || $is_in_includes_check) ? "../assets/profile_pics/" : "./assets/profile_pics/";

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $username = $_SESSION['username'] ?? 'User';
    if (!empty($_SESSION['profile_picture'])) {
        $profile_pic_url = $profile_pics_path . htmlspecialchars($_SESSION['profile_picture']);
    }
    else {
        $profile_pic_url = "https://ui-avatars.com/api/?name=" . urlencode($username) . "&background=ccaa00&color=090c11&size=128";
    }
}
else {
    $profile_pic_url = "https://ui-avatars.com/api/?name=User&background=ccaa00&color=090c11&size=128";
}

// Determine current page for active link highlighting
if (!isset($current_page)) {
    $current_page = basename($_SERVER['PHP_SELF']);
}
$is_home = ($current_page == 'home.php');
$is_explore = ($current_page == 'explore.php');
$is_watchlist = ($current_page == 'watchlist.php');
$is_profile = ($current_page == 'profile.php' || $current_page == 'edit_profile.php');
$is_about = ($current_page == 'about.php');
$is_contact = ($current_page == 'contact.php');

// Determine path prefix based on file location
$is_in_sub = ($is_in_pages_check || $is_in_includes_check);
$home_path = $is_in_sub ? '../home.php' : './home.php';
$explore_path = $is_in_sub ? ($is_in_pages_check ? 'explore.php' : '../pages/explore.php') : './pages/explore.php';
$watchlist_path = $is_in_sub ? ($is_in_pages_check ? 'watchlist.php' : '../pages/watchlist.php') : './pages/watchlist.php';
$profile_path = $is_in_sub ? ($is_in_pages_check ? 'profile.php' : '../pages/profile.php') : './pages/profile.php';
$about_path = $is_in_sub ? ($is_in_pages_check ? 'about.php' : '../pages/about.php') : './pages/about.php';
$contact_path = $is_in_sub ? ($is_in_pages_check ? 'contact.php' : '../pages/contact.php') : './pages/contact.php';
$logo_path = $is_in_sub ? '../assets/logo.ico' : './assets/logo.ico';
$search_icon_path = $is_in_sub ? '../assets/icons/search.png' : './assets/icons/search.png';
$search_script_path = $is_in_sub ? ($is_in_includes_check ? 'search.php' : '../includes/search.php') : './includes/search.php';
$css_path = $is_in_sub ? '../css/shared_styles.css' : './css/shared_styles.css';
$js_path = $is_in_sub ? '../js/main.js' : './js/main.js';
?>
<link rel="stylesheet" href="<?php echo $css_path; ?>">
<header>
    <div class="logo">
        <a href="<?php echo $home_path; ?>"><img src="<?php echo $logo_path; ?>" alt="Logo"></a>
    </div>
    <button class="hamburger" aria-label="Toggle menu">
        <span></span><span></span><span></span>
    </button>
    <nav>
        <a href="<?php echo $home_path; ?>" class="<?php echo $is_home ? 'active' : ''; ?>">Home</a>
        <a href="<?php echo $explore_path; ?>" class="<?php echo $is_explore ? 'active' : ''; ?>">Movies</a>
        <a href="<?php echo $watchlist_path; ?>" class="<?php echo $is_watchlist ? 'active' : ''; ?>">Watchlist</a>
        <a href="<?php echo $about_path; ?>" class="<?php echo $is_about ? 'active' : ''; ?>">About</a>
        <a href="<?php echo $contact_path; ?>" class="<?php echo $is_contact ? 'active' : ''; ?>">Contact</a>
        <a href="<?php echo $profile_path; ?>" class="<?php echo $is_profile ? 'active' : ''; ?>"><?php echo htmlspecialchars($username ?: 'Profile'); ?></a>
    </nav>
    <div class="search-container">
        <input type="search" id="searchQuery" name="searchbar" placeholder="Search Anime">
        <button type="button" onclick="performSearch()">
            <img src="<?php echo $search_icon_path; ?>" alt="Search">
        </button>
    </div>
</header>
<script>
    function performSearch() {
        const query = document.getElementById('searchQuery').value.trim();
        if (query) {
            window.location.href = `<?php echo $search_script_path; ?>?query=${encodeURIComponent(query)}`;
        } else {
            alert('Please enter a search term.');
        }
    }
</script>
<script src="<?php echo $js_path; ?>"></script>
