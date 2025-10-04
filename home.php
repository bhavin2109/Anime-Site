<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ./pages/login.php");
    exit();
}

// Use the correct user_id for queries
$user_id = $_SESSION['user_id'] ?? ($_SESSION['id'] ?? null);

// Include database connection
include './includes/dbconnect.php';

// Fetch trending anime randomly (for featured section)
$trendingAnimeQuery = "SELECT * FROM anime ORDER BY RAND() LIMIT 10";
$trendingAnimeResult = $conn->query($trendingAnimeQuery);

if (!$trendingAnimeResult) {
    error_log('Trending anime query failed: ' . mysqli_error($conn));
    $trendingAnime = [];
} else {
    $trendingAnime = [];
    while ($anime = $trendingAnimeResult->fetch_assoc()) {
        $trendingAnime[] = $anime;
    }
}

// Fetch featured anime (pick 1 random anime for the featured section)
$featuredAnimeQuery = "SELECT * FROM anime ORDER BY RAND() LIMIT 1";
$featuredAnimeResult = $conn->query($featuredAnimeQuery);
$featuredAnime = null;
if ($featuredAnimeResult && $featuredAnimeResult->num_rows > 0) {
    $featuredAnime = $featuredAnimeResult->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="./assets/logo.ico">

    <title>Home</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg,rgb(39, 59, 52) 0%,rgba(0, 0, 0, 0.48) 100%);
            color: #333;
        }

        header {
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            padding: 10px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .logo img {
            height: 30px
        }

        .options a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .options a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .search-section {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }

        .search-section input {
            padding: 10px;
            border: none;
            border-radius: 5px 0 0 5px;
            background: transparent;
            transition: 0.3s ease-in-out;
        }

        .search-section input::placeholder {
            color: #fff;
        }

        .search-section input:focus {
            outline: none;
        }

        .search-section input:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .search-section input:focus::placeholder {
            color: transparent;
        }

        .search-section button {
            padding: 8px;
            border: none;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            background: transparent;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .search-section button img {
            width: 20px;
            height: 20px;
        }

        .search-section button:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        main {
            display: flex;
            flex-direction: column;
            margin-top: 5vh;
        }

        /* --- Featured Anime Section --- */
        .featured-anime-section {
            width: 90%;
            height: 60vh;
            margin: 0px auto 0 auto;
            padding: 30px 20px;
            background: linear-gradient(135deg, #f8ffae 0%, #43c6ac 100%);
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.10);
            display: flex;
            align-items: center;
            gap: 32px;
        }
        .featured-anime-image {
            flex: 0 0 180px;
            max-width: 180px;
            max-height: 270px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.12);
            background: #fff;
        }
        .featured-anime-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }
        .featured-anime-info {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .featured-anime-title {
            font-size: 2rem;
            font-weight: bold;
            color: #222;
        }
        .featured-anime-meta {
            font-size: 1.1rem;
            color: #444;
        }
        .featured-anime-link {
            margin-top: 16px;
            display: inline-block;
            background: #43c6ac;
            color: #fff;
            padding: 10px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.2s;
        }
        .featured-anime-link:hover {
            background: #2e8b7a;
        }
        @media (max-width: 700px) {
            .featured-anime-section {
                flex-direction: column;
                align-items: flex-start;
                padding: 20px 10px;
            }
            .featured-anime-image {
                margin-bottom: 10px;
            }
        }
        /* --- End Featured Anime Section --- */

        /* --- Anime Section Horizontal Redesign --- */
        .genre-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            justify-content: center;
            margin: 20px 0;
            padding: 0 20px;
        }

        .genre-box {
            width: 100%;
            height: 150vh;
            background: linear-gradient(135deg, cyan, pink, cyan);
            background-size: 300% 300%;
            animation: gradient-animation 15s ease infinite;
            border-radius: 10px;
            overflow-y: auto;
            scroll-behavior: smooth;
            scrollbar-width: none;
            padding: 20px;
        }

        @keyframes gradient-animation {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .genre-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .anime-grid {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .anime-item {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #333;
            transition: 0.3s;
            padding: 10px;
            border-radius: 5px;
            background: transparent;
        }

        .anime-item:hover {
            background-color: #e0e0e0;
        }

        .anime-item img {
            width: 100px;
            height: 150px;
            border-radius: 4px;
            margin-right: 20px;
        }

        .anime-details {
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
            height: 100%;
        }

        .anime-details .anime_name {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .anime-details .anime-info {
            font-size: 14px;
            color: #666;
        }

        .anime-info {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 10px;
        }

        .additional-section {
            margin: 20px 0;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .additional-section h2 {
            margin-bottom: 20px;
        }

        .additional-section p {
            margin-bottom: 10px;
        }
        /* --- End Anime Section Vertical Redesign --- */

        .footer-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            justify-content: center;
            padding: 50px 150px;
            background-color: #333;
            color: #fff;
            height: auto;
        }

        .contact-us ul {
            list-style: none;
            padding: 0;
        }

        .contact-us ul li {
            margin: 10px 0;
            padding: 5px;
        }

        .contact-us ul li a {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .contact-us ul li a img {
            width: 24px;
            height: 24px;
            margin-right: 10px;
        }

        .feedback-container input {
            display: block;
            width: 100%;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            padding: 10px;
            box-sizing: border-box;
        }

        .feedback-container input[type="text"] {
            height: 150px;
        }

        .submit-btn {
            background-color: #fff;
            color: #333;
            border: none;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
            border-radius: 5px;
        }

        .submit-btn:hover {
            background-color: #ddd;
        }

        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                align-items: flex-start;
            }

            .options {
                margin-top: 10px;
            }

            .search-section {
                margin-top: 10px;
            }

            .footer-container {
                grid-template-columns: 1fr;
            }
        }

        .additional-section {
            margin: 20px 0;
            padding: 20px;
            width: 98%;
            justify-self: center;
            align-self: center;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .additional-section h2 {
            margin-bottom: 20px;
        }

        .additional-section p {
            margin-bottom: 10px;
        }

        .movie-container {
            display: grid;
            width: 100%;
            align-self: flex-start;
            justify-self: first baseline;
            gap: 20px;
            justify-content: center;
            margin: 20px 0;
            padding: 0 20px;
        }

        .movie-box {
            width: 100%;
            height: 50vh;
            display: flex;
            align-items: center;
            justify-content: space-evenly;
            background: linear-gradient(135deg, cyan, pink, blue);
            background-size: 300% 300%;
            border-radius: 10px;
            overflow-x: auto;
            overflow-y: hidden;
            scroll-behavior: smooth;
            scrollbar-width: none;
            padding: 20px;
        }

        .movie-grid {
            display: flex;
            flex-wrap: nowrap;
            gap: 30px;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .movie-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #333;
            transition: 0.3s;
            padding: 10px;
            border-radius: 5px;
            background: transparent;
        }

        .movie-item:hover {
            background-color: #e0e0e0;
        }

        .movie-item img {
            width: 180px;
            height: 270px;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .movie-details {
            text-align: center;
        }

        .movie_name {
            font-size: 16px;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <nav>
            <div class="logo">
                <img src="./assets/logo.ico" alt="Logo">
            </div>
            <div class="options">
                <a href="home.php">Home</a>
                <a href="./pages/explore.php">Movies</a>
                <a href="./pages/watchlist.php">Watchlist</a>
                <a href="./admin/admin.php">Admin</a>
                <a href="./pages/profile.php">Profile</a>
            </div>
            <div class="search-section">
                <input type="search" id="searchQuery" name="searchbar" placeholder="Search Anime">
                <button onclick="performSearch()"><img src="./assets/icons/search.png"></button>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <!-- Featured Anime Section -->
        <?php if ($featuredAnime): ?>
        <section class="featured-anime-section">
            <div class="featured-anime-image">
                <img src="./assets/thumbnails/<?php echo htmlspecialchars($featuredAnime['anime_image']); ?>" alt="<?php echo htmlspecialchars($featuredAnime['anime_name']); ?>">
            </div>
            <div class="featured-anime-info">
                <div class="featured-anime-title"><?php echo htmlspecialchars($featuredAnime['anime_name']); ?></div>
                <div class="featured-anime-meta">
                    <?php if (!empty($featuredAnime['genre'])): ?>
                        <span><strong>Genre:</strong> <?php echo htmlspecialchars($featuredAnime['genre']); ?></span><br>
                    <?php endif; ?>
                    <?php if (!empty($featuredAnime['anime_type'])): ?>
                        <span><strong>Type:</strong> <?php echo htmlspecialchars($featuredAnime['anime_type']); ?></span><br>
                    <?php endif; ?>
                    <?php if (!empty($featuredAnime['description'])): ?>
                        <span><?php echo htmlspecialchars(mb_strimwidth($featuredAnime['description'], 0, 180, "...")); ?></span>
                    <?php endif; ?>
                </div>
                <a class="featured-anime-link" href="./includes/player.php?anime_id=<?php echo htmlspecialchars($featuredAnime['anime_id']); ?>&episode=1">Watch Now</a>
            </div>
        </section>
        <?php endif; ?>

        <h2 style="text-align: center; margin: 20px 0; color:#fff;">Anime</h2>
        

<section class="genre-container">
    <?php
$genres = ['Action', 'Shounen', 'Romance', 'Fantasy']; // Define the genres you want to display
$animeByGenre = [];

foreach ($genres as $genre) {
    $query = "
        SELECT 
            a.anime_id, 
            a.anime_name, 
            a.anime_image, 
            a.anime_type, 
            COUNT(e.episode_id) AS episode_count
        FROM 
            anime a
        LEFT JOIN 
            episodes e ON a.anime_id = e.anime_id
        WHERE 
            a.genre = ? AND a.anime_type = 'TV' AND e.episode_id IS NOT NULL
        GROUP BY 
            a.anime_id, a.anime_name, a.anime_image, a.anime_type
        ORDER BY
            RAND()
        LIMIT 10
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $genre);
    $stmt->execute();
    $result = $stmt->get_result();
    $animeByGenre[$genre] = $result->fetch_all(MYSQLI_ASSOC);
}
?>
            <?php foreach ($genres as $genre): ?>
                <div class="genre-box">
                    <h2><?php echo htmlspecialchars($genre); ?></h2>
                    <div class="anime-grid">
                        <?php if (!empty($animeByGenre[$genre])): ?>
                            <?php foreach ($animeByGenre[$genre] as $anime): ?>
                                <a href="./includes/player.php?anime_id=<?php echo htmlspecialchars($anime['anime_id']); ?>&episode=1" class="anime-item">
                                    <img src="./assets/thumbnails/<?php echo htmlspecialchars($anime['anime_image']); ?>">
                                    <div class="anime-details">
                                        <div class="anime_name"><?php echo htmlspecialchars(isset($anime['anime_name']) ? $anime['anime_name'] : 'Unknown Title'); ?></div>
                                        <div class="anime-info">
                                            <span>Episodes: <?php echo htmlspecialchars(isset($anime['episode_count']) ? $anime['episode_count'] : 'N/A'); ?></span>
                                            <span>Type: <?php echo htmlspecialchars(isset($anime['anime_type']) ? $anime['anime_type'] : 'N/A'); ?></span>
                                        </form>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No anime found in this genre.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>

        <h2 style="text-align: center; margin: 20px 0; color:#fff;">Continue Watching</h2>
        <section class="movie-container">
            <?php
            // "Continue Watching" section: fetch recently watched anime for this user using only the history table (id, user_id, anime_id, episode_id, watched_at)

            $continueWatching = [];

            // The main problem: $user_id is not set or is not the correct session variable.
            // Fix: Use $user_id from session, as set above.

            if (isset($user_id) && is_numeric($user_id)) {
                // Get the most recent history entry for each anime watched by this user
                $cwQuery = "
                    SELECT h.anime_id, h.episode_id, h.watched_at
                    FROM history h
                    INNER JOIN (
                        SELECT anime_id, MAX(watched_at) AS max_watched_at
                        FROM history
                        WHERE user_id = ?
                        GROUP BY anime_id
                    ) latest
                    ON h.anime_id = latest.anime_id AND h.watched_at = latest.max_watched_at
                    WHERE h.user_id = ?
                    ORDER BY h.watched_at DESC
                    LIMIT 10
                ";
                if ($stmt = $conn->prepare($cwQuery)) {
                    $stmt->bind_param("ii", $user_id, $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $historyDetails = [];
                    while ($row = $result->fetch_assoc()) {
                        $historyDetails[$row['anime_id']] = $row;
                    }
                    $stmt->close();

                    // Now fetch anime and episode details for each
                    foreach ($historyDetails as $anime_id => $history) {
                        // Get anime info
                        $animeQuery = "SELECT anime_id, anime_name, anime_image, anime_type FROM anime WHERE anime_id = ?";
                        $animeData = null;
                        if ($animeStmt = $conn->prepare($animeQuery)) {
                            $animeStmt->bind_param("i", $anime_id);
                            $animeStmt->execute();
                            $animeResult = $animeStmt->get_result();
                            $animeData = $animeResult->fetch_assoc();
                            $animeStmt->close();
                        }

                        // Get episode info
                        $episode_url = null;
                        if (!empty($history['episode_id'])) {
                            $epQuery = "SELECT episode_url FROM episodes WHERE episode_id = ?";
                            if ($epStmt = $conn->prepare($epQuery)) {
                                $epStmt->bind_param("i", $history['episode_id']);
                                $epStmt->execute();
                                $epResult = $epStmt->get_result();
                                $epData = $epResult->fetch_assoc();
                                $epStmt->close();
                                $episode_url = $epData['episode_url'] ?? null;
                            }
                        }

                        if ($animeData) {
                            $continueWatching[] = [
                                'anime_id' => $animeData['anime_id'],
                                'anime_name' => $animeData['anime_name'],
                                'anime_image' => $animeData['anime_image'],
                                'anime_type' => $animeData['anime_type'],
                                'last_episode_number' => $episode_url,
                                'watched_at' => $history['watched_at']
                            ];
                        }
                    }
                }
            }
            ?>

            <?php if (!empty($continueWatching)): ?>
                <div class="movie-box">
                    <div class="movie-grid">
                        <?php foreach ($continueWatching as $cw): ?>
                            <a href="./includes/player.php?anime_id=<?php echo htmlspecialchars($cw['anime_id']); ?>&episode=<?php echo htmlspecialchars($cw['last_episode_number'] ?? 1); ?>" class="movie-item">
                                <img src="./assets/thumbnails/<?php echo htmlspecialchars($cw['anime_image']); ?>" alt="<?php echo htmlspecialchars($cw['anime_name']); ?>">
                                <div class="movie-details">
                                    <div class="movie_name"><?php echo htmlspecialchars($cw['anime_name']); ?></div>
                                    
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: #fff;">No anime watched recently</p>
            <?php endif; ?>
        </section>
        

        <h2 style="text-align: center; margin: 20px 0; color:#fff;">Movies</h2>

        <section class="movie-container">
            <?php
            // Fetch trending movies randomly
            // Only select movies that have at least one episode
            $trendingMoviesQuery = "
                SELECT a.* 
                FROM anime a
                WHERE a.anime_type = 'Movie'
                  AND EXISTS (
                      SELECT 1 FROM episodes e WHERE e.anime_id = a.anime_id
                  )
                ORDER BY RAND() LIMIT 10
            ";
            $trendingMoviesResult = $conn->query($trendingMoviesQuery);

            // Check if the query was successful
            if (!$trendingMoviesResult) {
                error_log('Trending movies query failed: ' . mysqli_error($conn));
                $trendingMovies = [];
            } else {
                $trendingMovies = [];
                while ($movie = $trendingMoviesResult->fetch_assoc()) {
                    $trendingMovies[] = $movie;
                }
            }
            ?>

            <div class="movie-box">
                <div class="movie-grid">
                    <?php if (!empty($trendingMovies)): ?>
                        <?php foreach ($trendingMovies as $movie): ?>
                            <a href="./includes/player.php?anime_id=<?php echo htmlspecialchars($movie['anime_id']); ?>" class="movie-item">
                                <img src="./assets/thumbnails/<?php echo htmlspecialchars($movie['anime_image']); ?>" alt="<?php echo htmlspecialchars($movie['anime_name']); ?>">
                                <div class="movie-details">
                                    <div class="movie_name"><?php echo htmlspecialchars($movie['anime_name']); ?></div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No trending movies found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <h2 style="text-align: center; margin: 20px 0; color:#fff;">Upcoming</h2>

    <section class="movie-container">
        <?php
        // Fetch upcoming anime (anime with zero episodes)
        $upcomingAnimeQuery = "SELECT * FROM anime WHERE  anime_id NOT IN (SELECT anime_id FROM episodes) ORDER BY RAND() LIMIT 10";
        $upcomingAnimeResult = $conn->query($upcomingAnimeQuery);

        // Check if the query was successful
        if (!$upcomingAnimeResult) {
            error_log('Upcoming anime query failed: ' . mysqli_error($conn));
            $upcomingAnime = [];
        } else {
            $upcomingAnime = [];
            while ($anime = $upcomingAnimeResult->fetch_assoc()) {
                $upcomingAnime[] = $anime;
            }
        }
        ?>

        <div class="movie-box">
            <div class="movie-grid">
                <?php if (!empty($upcomingAnime)): ?>
                    <?php foreach ($upcomingAnime as $anime): ?>
                        <a href="./includes/player.php?anime_id=<?php echo htmlspecialchars($anime['anime_id']); ?>" class="movie-item">
                            <img src="./assets/thumbnails/<?php echo htmlspecialchars($anime['anime_image']); ?>" alt="<?php echo htmlspecialchars($anime['anime_name']); ?>">
                            <div class="movie-details">
                                <div class="movie_name"><?php echo htmlspecialchars($anime['anime_name']); ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No upcoming anime found.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <section class="footer-container">
            <div class="contact-us">
                <h2>Contact Us</h2>
                <div class="contact-us-container">
                    <ul type="none">
                        <li><a href="https://www.instagram.com/bleach_tbh?igsh=cTBmM20zM2M4OWFs"><img src="./assets/icons/instagram.png">Instagram</a></li>
                        <li><a href="#"><img src="./assets/icons/telegram.png">Telegram</a></li>
                        <li><a href="#"><img src="./assets/icons/twitter.png">X</a></li>
                        <li><a href="#"><img src="./assets/icons/facebook.png">Facebook</a></li>
                        <li><a href="#"><img src="./assets/icons/gmail.png">G-Mail</a></li>
                    </ul>
                </div>
            </div>

            <div class="feedback-form">
                <h2>Feedback</h2>
                <form action="" class="feedback-container" name="feedback-form" method="post">
                    <input type="email" name="emailid" class="email-feedback" placeholder="E-Mail">
                    <input type="text" name="feedback-text" class="text-feedback" placeholder="Feedback">
                    <input type="button" value="Submit" name="submitok" class="submit-btn">
                </form>
            </div>
        </section>
        <p style="height: 5vh; width: 100%; display: flex; align-items:center; justify-content:center; color:#fff;">&copy; Group No.1</p>
    </footer>

    <script>
        function performSearch() {
            const query = document.getElementById('searchQuery').value.trim();
            if (query) {
                window.location.href = `./includes/search.php?query=${encodeURIComponent(query)}`;
            } else {
                alert('Please enter a search term.');
            }
        }
    </script>
</body>

</html>
<?php
$conn->close();
?>