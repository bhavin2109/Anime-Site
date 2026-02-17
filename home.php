<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ./pages/login.php");
    exit();
}

$user_id = null;
if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
    $user_id = (int)$_SESSION['user_id'];
}
elseif (isset($_SESSION['id']) && is_numeric($_SESSION['id'])) {
    $user_id = (int)$_SESSION['id'];
}
elseif (isset($_SESSION['loggedin']) && is_numeric($_SESSION['loggedin'])) {
    $user_id = (int)$_SESSION['loggedin'];
}

if (!$user_id) {
    header("Location: ./pages/login.php");
    exit();
}

include './includes/dbconnect.php';

$trendingAnimeQuery = "SELECT * FROM anime ORDER BY RAND() LIMIT 10";
$trendingAnimeResult = $conn->query($trendingAnimeQuery);
$trendingAnime = [];
if ($trendingAnimeResult) {
    while ($anime = $trendingAnimeResult->fetch_assoc()) {
        $trendingAnime[] = $anime;
    }
}

$featuredAnimeQuery = "
    SELECT a.*
    FROM anime a
    WHERE (SELECT COUNT(*) FROM episodes e WHERE e.anime_id = a.anime_id) > 0
    ORDER BY RAND() LIMIT 1
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
    <title>Home — Anime Streaming</title>
    <link rel="stylesheet" href="./css/shared_styles.css">
    <link rel="stylesheet" href="./css/pages/home.css">
</head>
<body>
    <?php
$current_page = 'home.php';
include './includes/header_shared.php';
?>

    <main>
        <!-- Featured Anime Hero Section -->
        <?php if ($featuredAnime): ?>
        <section class="featured-anime-section">
            <canvas id="particles-canvas"></canvas>
            <div class="featured-anime-image">
                <img src="./assets/thumbnails/<?php echo htmlspecialchars($featuredAnime['anime_image']); ?>" alt="<?php echo htmlspecialchars($featuredAnime['anime_name']); ?>">
            </div>
            <div class="featured-anime-info">
                <div class="featured-anime-title"><?php echo htmlspecialchars($featuredAnime['anime_name']); ?></div>
                <div class="featured-anime-meta">
                    <?php if (!empty($featuredAnime['genre'])): ?>
                        <span><strong>Genre:</strong> <?php echo htmlspecialchars($featuredAnime['genre']); ?></span><br>
                    <?php
    endif; ?>
                    <?php if (!empty($featuredAnime['anime_type'])): ?>
                        <span><strong>Type:</strong> <?php echo htmlspecialchars($featuredAnime['anime_type']); ?></span><br>
                    <?php
    endif; ?>
                    <?php if (!empty($featuredAnime['description'])): ?>
                        <span><?php echo htmlspecialchars(mb_strimwidth($featuredAnime['description'], 0, 180, "...")); ?></span>
                    <?php
    endif; ?>
                </div>
                <a class="featured-anime-link" href="./includes/player.php?anime_id=<?php echo htmlspecialchars($featuredAnime['anime_id']); ?>&episode=1">Watch Now</a>
            </div>
        </section>
        <?php
endif; ?>

        <h2 class="section-title reveal">Anime</h2>

        <section class="genre-container stagger-children">
            <?php
$genres = ['Adventure', 'Shounen', 'Romance', 'Seinen'];
$animeByGenre = [];
foreach ($genres as $genre) {
    $query = "SELECT a.anime_id, a.anime_name, a.anime_image, a.anime_type, COUNT(e.episode_id) AS episode_count
                    FROM anime a LEFT JOIN episodes e ON a.anime_id = e.anime_id
                    WHERE a.genre = ? AND a.anime_type = 'TV' AND e.episode_id IS NOT NULL
                    GROUP BY a.anime_id, a.anime_name, a.anime_image, a.anime_type
                    ORDER BY RAND() LIMIT 10";
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
                                        <div class="anime_name"><?php echo htmlspecialchars($anime['anime_name'] ?? 'Unknown Title'); ?></div>
                                        <div class="anime-info">
                                            <span>Episodes: <?php echo htmlspecialchars($anime['episode_count'] ?? 'N/A'); ?></span>
                                            <span>Type: <?php echo htmlspecialchars($anime['anime_type'] ?? 'N/A'); ?></span>
                                        </div>
                                    </div>
                                </a>
                            <?php
        endforeach; ?>
                        <?php
    else: ?>
                            <p style="color: #e5e7eb;">No anime found in this genre.</p>
                        <?php
    endif; ?>
                    </div>
                </div>
            <?php
endforeach; ?>
        </section>

        <?php
// Continue Watching
$continueWatching = [];
if ($user_id && is_numeric($user_id)) {
    $cwQuery = "SELECT h.anime_id, h.episode_id, h.watched_at FROM history h
                INNER JOIN (SELECT anime_id, MAX(watched_at) AS max_watched_at FROM history WHERE user_id = ? GROUP BY anime_id) latest
                ON h.anime_id = latest.anime_id AND h.watched_at = latest.max_watched_at
                WHERE h.user_id = ? ORDER BY h.watched_at DESC LIMIT 10";
    if ($stmt = $conn->prepare($cwQuery)) {
        $stmt->bind_param("ii", $user_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $historyDetails = [];
        while ($row = $result->fetch_assoc()) {
            $historyDetails[$row['anime_id']] = $row;
        }
        $stmt->close();

        foreach ($historyDetails as $anime_id => $history) {
            $animeQuery = "SELECT anime_id, anime_name, anime_image, anime_type FROM anime WHERE anime_id = ?";
            $animeData = null;
            if ($animeStmt = $conn->prepare($animeQuery)) {
                $animeStmt->bind_param("i", $anime_id);
                $animeStmt->execute();
                $animeResult = $animeStmt->get_result();
                $animeData = $animeResult->fetch_assoc();
                $animeStmt->close();
            }
            if ($animeData) {
                $continueWatching[] = [
                    'anime_id' => $animeData['anime_id'],
                    'anime_name' => $animeData['anime_name'],
                    'anime_image' => $animeData['anime_image'],
                    'anime_type' => $animeData['anime_type'],
                    'last_episode_id' => $history['episode_id'],
                    'watched_at' => $history['watched_at']
                ];
            }
        }
    }
}
?>

        <?php if (!empty($continueWatching)): ?>
        <h2 class="section-title reveal">Continue Watching</h2>
        <section class="movie-container reveal">
            <div class="movie-box">
                <div class="movie-grid">
                    <?php foreach ($continueWatching as $cw): ?>
                        <a href="./includes/player.php?anime_id=<?php echo htmlspecialchars($cw['anime_id']); ?>&episode=<?php echo htmlspecialchars($cw['last_episode_id'] ?? 1); ?>" class="movie-item">
                            <img src="./assets/thumbnails/<?php echo htmlspecialchars($cw['anime_image']); ?>" alt="<?php echo htmlspecialchars($cw['anime_name']); ?>">
                            <div class="movie-details">
                                <div class="movie_name"><?php echo htmlspecialchars($cw['anime_name']); ?></div>
                            </div>
                        </a>
                    <?php
    endforeach; ?>
                </div>
            </div>
        </section>
        <?php
endif; ?>

        <h2 class="section-title reveal">Movies</h2>
        <section class="movie-container reveal">
            <?php
$trendingMoviesQuery = "SELECT a.* FROM anime a
                WHERE a.anime_type = 'Movie' AND EXISTS (SELECT 1 FROM episodes e WHERE e.anime_id = a.anime_id)
                ORDER BY RAND() LIMIT 10";
$trendingMoviesResult = $conn->query($trendingMoviesQuery);
$trendingMovies = [];
if ($trendingMoviesResult) {
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
                        <?php
    endforeach; ?>
                    <?php
else: ?>
                        <p style="color: #e5e7eb;">No trending movies found.</p>
                    <?php
endif; ?>
                </div>
            </div>
        </section>

        <h2 class="section-title reveal">Upcoming</h2>
        <section class="movie-container reveal">
            <?php
$upcomingAnimeQuery = "SELECT * FROM anime WHERE anime_id NOT IN (SELECT anime_id FROM episodes) ORDER BY RAND() LIMIT 10";
$upcomingAnimeResult = $conn->query($upcomingAnimeQuery);
$upcomingAnime = [];
if ($upcomingAnimeResult) {
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
                        <?php
    endforeach; ?>
                    <?php
else: ?>
                        <p style="color: #e5e7eb;">No upcoming anime found.</p>
                    <?php
endif; ?>
                </div>
            </div>
        </section>
    </main>

    <?php include './includes/footer_shared.php'; ?>
    <script src="./js/particles.js"></script>
</body>
</html>
<?php $conn->close(); ?>