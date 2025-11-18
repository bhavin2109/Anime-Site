<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$profile_pic_url = '';
$username = '';
$is_in_pages_check = (strpos($_SERVER['PHP_SELF'], '/pages/') !== false);
$profile_pics_path = $is_in_pages_check ? "../assets/profile_pics/" : "./assets/profile_pics/";

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $username = $_SESSION['username'] ?? 'User';
    if (!empty($_SESSION['profile_picture'])) {
        $profile_pic_url = $profile_pics_path . htmlspecialchars($_SESSION['profile_picture']);
    } else {
        $profile_pic_url = "https://ui-avatars.com/api/?name=" . urlencode($username) . "&background=dc2626&color=fff&size=128";
    }
} else {
    $profile_pic_url = "https://ui-avatars.com/api/?name=User&background=dc2626&color=fff&size=128";
}

// Determine current page for active link highlighting
if (!isset($current_page)) {
    $current_page = basename($_SERVER['PHP_SELF']);
}
$is_home = ($current_page == 'home.php');
$is_explore = ($current_page == 'explore.php');
$is_watchlist = ($current_page == 'watchlist.php');
$is_profile = ($current_page == 'profile.php' || $current_page == 'edit_profile.php');

// Determine path prefix based on file location
$is_in_pages = (strpos($_SERVER['PHP_SELF'], '/pages/') !== false);
$home_path = $is_in_pages ? '../home.php' : './home.php';
$explore_path = $is_in_pages ? 'explore.php' : './pages/explore.php';
$watchlist_path = $is_in_pages ? 'watchlist.php' : './pages/watchlist.php';
$profile_path = $is_in_pages ? 'profile.php' : './pages/profile.php';
$logo_path = $is_in_pages ? '../assets/logo.ico' : './assets/logo.ico';
$search_icon_path = $is_in_pages ? '../assets/icons/search.png' : './assets/icons/search.png';
$search_script_path = $is_in_pages ? '../includes/search.php' : './includes/search.php';
$css_path = $is_in_pages ? '../css/shared_styles.css' : './css/shared_styles.css';
?>
<link rel="stylesheet" href="<?php echo $css_path; ?>">
<header>
    <nav>
        <div class="logo">
            <a href="<?php echo $home_path; ?>"><img src="<?php echo $logo_path; ?>" alt="Logo"></a>
        </div>
        <div class="nav-center">
            <div class="options">
                <a href="<?php echo $home_path; ?>" class="<?php echo $is_home ? 'active' : ''; ?>">Home</a>
                <a href="<?php echo $explore_path; ?>" class="<?php echo $is_explore ? 'active' : ''; ?>">Movies</a>
                <a href="<?php echo $watchlist_path; ?>" class="<?php echo $is_watchlist ? 'active' : ''; ?>">Watchlist</a>
            </div>
            <a href="<?php echo $profile_path; ?>" class="profile-link">
                <img src="<?php echo $profile_pic_url; ?>" alt="Profile">
                <span><?php echo htmlspecialchars($username); ?></span>
            </a>
        </div>
        <div class="search-section">
            <input type="search" id="searchQuery" name="searchbar" placeholder="Search Anime">
            <button type="button" onclick="performSearch()">
                <img src="<?php echo $search_icon_path; ?>" alt="Search">
            </button>
        </div>
    </nav>
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

