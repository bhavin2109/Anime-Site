<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ./pages/login.php");
    exit();
}

// Include database connection
include '../includes/dbconnect.php';

// Fetch anime by genre with episode count (only movies with at least one episode)
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
            a.genre = ? AND a.anime_type = 'Movie'
        GROUP BY 
            a.anime_id, a.anime_name, a.anime_image, a.anime_type
        HAVING 
            episode_count > 0
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

// Fetch upcoming movies (movies with no episodes)
$upcomingMoviesQuery = "
    SELECT 
        a.anime_id, 
        a.anime_name, 
        a.anime_image, 
        a.anime_type
    FROM 
        anime a
    LEFT JOIN 
        episodes e ON a.anime_id = e.anime_id
    WHERE 
        a.anime_type = 'Movie'
    GROUP BY 
        a.anime_id, a.anime_name, a.anime_image, a.anime_type
    HAVING 
        COUNT(e.episode_id) = 0
    ORDER BY
        RAND()
    LIMIT 10
";
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
    <title>Explore</title>
    <link rel="stylesheet" href="../css/shared_styles.css">
    <style>

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
            text-align: center;
            margin-bottom: 20px;
            color: #ffffff;
            text-shadow: 0 2px 10px rgba(220, 38, 38, 0.5);
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

        /* Styles for upcoming movies section - matching home page */
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
            color: #e5e7eb;
        }
    </style>
</head>

<body>
    <?php 
    $current_page = 'explore.php';
    include '../includes/header_shared.php'; 
    ?>

    <!-- Main Content -->
    <main>

        <section class="genre-container">
            <?php foreach ($genres as $genre): ?>
                <div class="genre-box">
                    <h2><?php echo htmlspecialchars($genre); ?></h2>
                    <div class="anime-grid">
                        <?php if (!empty($animeByGenre[$genre])): ?>
                            <?php foreach ($animeByGenre[$genre] as $anime): ?>
                                <a href="../includes/player.php?anime_id=<?php echo htmlspecialchars($anime['anime_id']); ?>&episode=1" class="anime-item">
                                    <img src="../assets/thumbnails/<?php echo htmlspecialchars($anime['anime_image']); ?>">
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

        <!-- Upcoming Movies Section -->
        <h2 style="text-align: center; margin: 20px 0;">Upcoming Movies</h2>
        <section class="movie-container">
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
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color: #e5e7eb;">No upcoming movies found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

    </main>
    <?php include '../includes/footer_shared.php'; ?>
</body>
</html>
<?php
$conn->close();
?>