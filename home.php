<?php
// --- SESSION MANAGEMENT: Reliable user login check & user_id retrieval ---
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ./pages/login.php");
    exit();
}

// Robust way to retrieve user_id for all new and old users
$user_id = null;
if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
    $user_id = (int)$_SESSION['user_id'];
} elseif (isset($_SESSION['id']) && is_numeric($_SESSION['id'])) {
    $user_id = (int)$_SESSION['id'];
} elseif (isset($_SESSION['loggedin']) && is_numeric($_SESSION['loggedin'])) {
    // Some systems use loggedin for user_id (rare)
    $user_id = (int)$_SESSION['loggedin'];
}

if (!$user_id) {
    // Fallback: force relogin. This shouldn't happen, but safe-guard
    header("Location: ./pages/login.php");
    exit();
}

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
$featuredAnimeQuery = "
    SELECT a.*
    FROM anime a
    WHERE (
        SELECT COUNT(*) FROM episodes e WHERE e.anime_id = a.anime_id
    ) > 0
    ORDER BY RAND() 
    LIMIT 1
";
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
    <link rel="stylesheet" href="./css/shared_styles.css">
    <style>

        main {
            display: flex;
            flex-direction: column;
            margin-top: 5vh;
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }

        /* --- Featured Anime Section --- */
        .featured-anime-section {
            width: 90%;
            height: 60vh;
            margin: 0px auto 0 auto;
            padding: 30px 20px;
            background: linear-gradient(135deg, #0a0a0a 0%, #1e3a5f 30%, #dc2626 60%, #0a0a0a 100%);
            background-size: 200% 200%;
            animation: featured-gradient 8s ease infinite;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(220, 38, 38, 0.4);
            display: flex;
            align-items: center;
            gap: 32px;
            border: 2px solid rgba(220, 38, 38, 0.3);
        }

        @keyframes featured-gradient {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .featured-anime-image {
            flex: 0 0 180px;
            width: 180px;
            height: 270px;
            min-width: 180px;
            min-height: 270px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(220, 38, 38, 0.4);
            background: rgba(26, 26, 26, 0.5);
            border: 2px solid rgba(220, 38, 38, 0.3);
        }
        .featured-anime-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
            display: block;
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
            color: #ffffff;
            text-shadow: 0 2px 10px rgba(220, 38, 38, 0.5);
        }
        .featured-anime-meta {
            font-size: 1.1rem;
            color: #e5e7eb;
        }
        .featured-anime-link {
            margin-top: 16px;
            display: inline-block;
            background: #dc2626;
            color: #fff;
            padding: 12px 28px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4);
        }
        .featured-anime-link:hover {
            background: #991b1b;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.6);
        }
        @media (max-width: 700px) {
            .featured-anime-section {
                flex-direction: column;
                align-items: center;
                padding: 20px 15px;
                height: auto;
                min-height: 60vh;
            }
            .featured-anime-image {
                margin-bottom: 20px;
                width: 150px;
                height: 225px;
                min-width: 150px;
                min-height: 225px;
            }
            .featured-anime-info {
                width: 100%;
                text-align: center;
            }
            .featured-anime-title {
                font-size: 1.5rem;
            }
            .featured-anime-meta {
                font-size: 1rem;
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
            background: linear-gradient(135deg, #0a0a0a 0%, #1e3a5f 25%, #dc2626 50%, #1e3a5f 75%, #0a0a0a 100%);
            background-size: 400% 400%;
            animation: gradient-animation 15s ease infinite;
            border-radius: 12px;
            overflow-y: auto;
            scroll-behavior: smooth;
            scrollbar-width: none;
            padding: 20px;
            box-shadow: 0 8px 24px rgba(220, 38, 38, 0.3);
            border: 2px solid rgba(220, 38, 38, 0.2);
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
            color: #ffffff;
            text-shadow: 0 2px 10px rgba(220, 38, 38, 0.5);
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
            color: #e5e7eb;
            transition: all 0.3s ease;
            padding: 10px;
            border-radius: 8px;
            background: rgba(26, 26, 26, 0.5);
            border: 1px solid rgba(220, 38, 38, 0.1);
        }

        .anime-item:hover {
            background: rgba(220, 38, 38, 0.2);
            border-color: rgba(220, 38, 38, 0.5);
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
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

        /* Remove footer styles, not needed for About Us section */

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
            background: linear-gradient(135deg, #0a0a0a 0%, #1e3a5f 30%, #dc2626 60%, #0a0a0a 100%);
            background-size: 300% 300%;
            animation: movie-gradient 10s ease infinite;
            border-radius: 12px;
            overflow-x: auto;
            overflow-y: hidden;
            scroll-behavior: smooth;
            scrollbar-width: none;
            padding: 20px;
            box-shadow: 0 8px 24px rgba(220, 38, 38, 0.3);
            border: 2px solid rgba(220, 38, 38, 0.2);
        }

        @keyframes movie-gradient {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
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
            color: #e5e7eb;
            transition: all 0.3s ease;
            padding: 10px;
            border-radius: 8px;
            background: rgba(26, 26, 26, 0.5);
            border: 1px solid rgba(220, 38, 38, 0.1);
        }

        .movie-item:hover {
            background: rgba(220, 38, 38, 0.2);
            border-color: rgba(220, 38, 38, 0.5);
            transform: scale(1.05) translateY(-5px);
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4);
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

        /* About Us Section Styles - Now using footer from shared */
        h2 {
            color: #ffffff;
            text-shadow: 0 2px 10px rgba(220, 38, 38, 0.3);
        }
        .about-us-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            align-items: start;
        }
        .about-us-section h2 {
            font-size: 2.2rem;
            margin-bottom: 30px;
            color: #f8ffae;
            letter-spacing: 1px;
            text-align: center;
            grid-column: 1 / -1;
        }
        .about-us-column {
            display: flex;
            flex-direction: column;
        }
        .about-us-column h3 {
            font-size: 1.4rem;
            margin-bottom: 15px;
            color: #f8ffae;
        }
        .about-us-section p {
            font-size: 1.1rem;
            color: #e0e0e0;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        .about-us-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .about-us-section li {
            margin-bottom: 10px;
            color: #e0e0e0;
            font-size: 1rem;
            line-height: 1.6;
            padding-left: 20px;
            position: relative;
        }
        .about-us-section li:before {
            content: "•";
            color: #f8ffae;
            font-weight: bold;
            position: absolute;
            left: 0;
        }
        .about-us-footer {
            grid-column: 1 / -1;
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            color: #bdbdbd;
        }
        @media (max-width: 900px) {
            .about-us-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 30px;
            }
            .about-us-section {
                padding: 32px 20px;
            }
        }
        @media (max-width: 600px) {
            .about-us-container {
                grid-template-columns: 1fr;
                gap: 25px;
            }
            .about-us-section {
                padding: 20px 15px;
            }
            .about-us-section h2 {
                font-size: 1.6rem;
                margin-bottom: 20px;
            }
            .about-us-column h3 {
                font-size: 1.2rem;
            }
            .about-us-section p {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <?php 
    $current_page = 'home.php';
    include './includes/header_shared.php'; 
    ?>

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

        <h2 style="text-align: center; margin: 20px 0;">Anime</h2>

<section class="genre-container">
    <?php
    $genres = ['Adventure', 'Shounen', 'Romance', 'Seinen']; // Define the genres you want to display
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
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: #e5e7eb;">No anime found in this genre.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</section>

<?php
// Fix: Get the correct user's continue watching history, not only '1'!

$continueWatching = [];

if ($user_id && is_numeric($user_id)) {
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

        // Now fetch anime and episode details for each entry
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
            // As in your previous logic, episodes table doesn't have episode_number, so just use id-as-number (for display only)
            $episode_number = (!empty($history['episode_id'])) ? $history['episode_id'] : null;
            $episode_title = null;

            if ($animeData) {
                $continueWatching[] = [
                    'anime_id' => $animeData['anime_id'],
                    'anime_name' => $animeData['anime_name'],
                    'anime_image' => $animeData['anime_image'],
                    'anime_type' => $animeData['anime_type'],
                    'last_episode_id' => $history['episode_id'],
                    'last_episode_number' => $episode_number,
                    'last_episode_title' => $episode_title,
                    'watched_at' => $history['watched_at']
                ];
            }
        }
    }
}
?>

<?php if (!empty($continueWatching)): ?>
<h2 style="text-align: center; margin: 20px 0;">Continue Watching</h2>
<section class="movie-container">
    <div class="movie-box">
        <div class="movie-grid">
            <?php foreach ($continueWatching as $cw): ?>
                <a href="./includes/player.php?anime_id=<?php echo htmlspecialchars($cw['anime_id']); ?>&episode=<?php echo htmlspecialchars($cw['last_episode_id'] ?? 1); ?>" class="movie-item">
                    <img src="./assets/thumbnails/<?php echo htmlspecialchars($cw['anime_image']); ?>" alt="<?php echo htmlspecialchars($cw['anime_name']); ?>">
                    <div class="movie-details">
                        <div class="movie_name"><?php echo htmlspecialchars($cw['anime_name']); ?></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<h2 style="text-align: center; margin: 20px 0;">Movies</h2>

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
                <p style="color: #e5e7eb;">No trending movies found.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
</main>

<h2 style="text-align: center; margin: 20px 0;">Upcoming</h2>

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
                <p style="color: #e5e7eb;">No upcoming anime found.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

    <?php include './includes/footer_shared.php'; ?>


</body>
</html>
<?php
$conn->close();
?>