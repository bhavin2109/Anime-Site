<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ./pages/login.php");
    exit();
}

// Include database connection
include '../includes/dbconnect.php';

// Fetch anime by genre with episode count
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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #000000, #1a1a1a, #333333, #000000);
            background-size: 300% 300%;
            animation: gradient-animation 4s ease infinite;
        }

        
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
            background: linear-gradient(135deg, cyan, pink, green);
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
    </style>
</head>

<body>
    <!-- Header -->
    <?php include '../includes/header.php'; ?>

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
                            <p>No anime found in this genre.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>

      

    </main>

    <!-- Footer -->
     <!--
    <?php include 'footer.php'; ?>
     -->
</body>

</html>
<?php
$conn->close();
?>