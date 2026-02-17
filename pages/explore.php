<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}
include '../includes/dbconnect.php';

$genres = ['Action', 'Shounen', 'Romance', 'Fantasy'];
$animeByGenre = [];
foreach ($genres as $genre) {
    $query = "SELECT a.anime_id, a.anime_name, a.anime_image, a.anime_type, COUNT(e.episode_id) AS episode_count
        FROM anime a LEFT JOIN episodes e ON a.anime_id = e.anime_id
        WHERE a.genre = ? AND a.anime_type = 'Movie'
        GROUP BY a.anime_id, a.anime_name, a.anime_image, a.anime_type
        HAVING episode_count > 0 ORDER BY RAND() LIMIT 10";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $genre);
    $stmt->execute();
    $result = $stmt->get_result();
    $animeByGenre[$genre] = $result->fetch_all(MYSQLI_ASSOC);
}

$upcomingMoviesQuery = "SELECT a.anime_id, a.anime_name, a.anime_image, a.anime_type
    FROM anime a LEFT JOIN episodes e ON a.anime_id = e.anime_id
    WHERE a.anime_type = 'Movie' GROUP BY a.anime_id, a.anime_name, a.anime_image, a.anime_type
    HAVING COUNT(e.episode_id) = 0 ORDER BY RAND() LIMIT 10";
$upcomingMoviesResult = $conn->query($upcomingMoviesQuery);
$upcomingMovies = [];
if ($upcomingMoviesResult) {
    $upcomingMovies = $upcomingMoviesResult->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/logo.ico">
    <title>Explore Movies — Anime Streaming</title>
    <link rel="stylesheet" href="../css/shared_styles.css">
    <link rel="stylesheet" href="../css/pages/explore.css">
</head>
<body>
    <?php $current_page = 'explore.php';
include '../includes/header_shared.php'; ?>
    <main>
        <h2 class="section-title reveal">Explore Movies</h2>
        <section class="genre-container stagger-children">
            <?php foreach ($genres as $genre): ?>
                <div class="genre-box">
                    <h2><?php echo htmlspecialchars($genre); ?></h2>
                    <div class="anime-grid">
                        <?php if (!empty($animeByGenre[$genre])): ?>
                            <?php foreach ($animeByGenre[$genre] as $anime): ?>
                                <a href="../includes/player.php?anime_id=<?php echo htmlspecialchars($anime['anime_id']); ?>&episode=1" class="anime-item">
                                    <img src="../assets/thumbnails/<?php echo htmlspecialchars($anime['anime_image']); ?>">
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

        <h2 class="section-title reveal">Upcoming Movies</h2>
        <section class="movie-container reveal">
            <div class="movie-box">
                <div class="movie-grid">
                    <?php if (!empty($upcomingMovies)): ?>
                        <?php foreach ($upcomingMovies as $movie): ?>
                            <a href="../includes/player.php?anime_id=<?php echo htmlspecialchars($movie['anime_id']); ?>" class="movie-item">
                                <img src="../assets/thumbnails/<?php echo htmlspecialchars($movie['anime_image']); ?>" alt="<?php echo htmlspecialchars($movie['anime_name']); ?>">
                                <div class="movie-details">
                                    <div class="movie_name"><?php echo htmlspecialchars($movie['anime_name']); ?></div>
                                </div>
                            </a>
                        <?php
    endforeach; ?>
                    <?php
else: ?>
                        <p style="color: #e5e7eb;">No upcoming movies found.</p>
                    <?php
endif; ?>
                </div>
            </div>
        </section>
    </main>
    <?php include '../includes/footer_shared.php'; ?>
</body>
</html>
<?php $conn->close(); ?>