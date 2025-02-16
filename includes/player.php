<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player</title>
    <style>
        /* Your existing CSS code */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: Arial, sans-serif;
            overflow: hidden;
        }

        .player-container {
            display: flex;
            height: 100%;
            justify-content: space-between;
            padding: 1px;
            overflow: hidden;
        }

        .sidebar {
            width: 300px;
            background-color: #bebaba;
            padding: 15px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            height: 100%;
            overflow-y: scroll;
        }

        .episode-list {
            list-style: none;
            padding: 15px 0px;
            margin-bottom: 5vh;
            display: flex;
            flex-direction: row;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: space-evenly;
        }

        .episode-list li {
            margin-top: 8px;
            background-color: rgb(168, 169, 169);
            padding: 5px;
            border-radius: 8px;
            transition: 0.3s;
            width: 20%;
            height: 6vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .episode-list li a {
            text-decoration: none;
            color: #333;
            font-size: 18px;
        }

        .episode-list li:hover {
            background-color: #939292;
        }

        .main-player {
            flex-grow: 1;
            text-align: center;
            height: 100%;
            overflow: hidden;
        }

        .video-player {
            width: 100%;
            height: 65vh;
            border: none;
        }

        .anime-info {
            width: 350px;
            background-color: #a1a1a1;
            padding: 25px;
            text-align: center;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            height: 100%;
            overflow-y: auto;
        }

        .anime-info img {
            width: 150px;
            height: 200px;
            margin-bottom: 20px;
        }

        .anime-info h2 {
            font-size: 24px;
            margin: 15px 0;
        }

        .anime-info p {
            font-size: 16px;
            color: #555;
            margin-bottom: 15px;
        }

        .anime-info form {
            margin-top: 15px;
        }

        .anime-info button {
            padding: 15px;
            margin-right: 15px;
            background-color: #5cb85c;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }

        .anime-info button:hover {
            background-color: #4cae4c;
        }

        .anime-info button:focus {
            outline: none;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="player-container">
        <!-- Sidebar for Episodes -->
        <div class="sidebar">
            <h3>List of Episodes</h3>
            <ul class="episode-list">
                <?php
                include 'dbconnect.php';
                // Fetch all episodes for the anime
                $anime_id = isset($_GET['anime_id']) ? intval($_GET['anime_id']) : 1; // Default to anime_id 1 if not provided
                $episode_id = isset($_GET['episode_id']) ? intval($_GET['episode_id']) : 1; // Default to 1 if not provided
                $episodes_query = "SELECT episode_id FROM episodes WHERE anime_id = ? ORDER BY episode_id ASC";
                $stmt = $conn->prepare($episodes_query);
                $stmt->bind_param("i", $anime_id);
                $stmt->execute();
                $episodes_result = $stmt->get_result();

                $episode_counter = 1; // Counter for sequential episode numbers
                $firstEpisode = null; // To store the first episode
                $current_episode_number = 1; // Default to 1

                while ($episode = $episodes_result->fetch_assoc()) {
                    if ($firstEpisode === null) {
                        $firstEpisode = $episode; // Set the first episode
                    }
                    if ($episode['episode_id'] == $episode_id) {
                        $current_episode_number = $episode_counter;
                    }
                    echo '<li><a href="?anime_id=' . $anime_id . '&episode_id=' . $episode['episode_id'] . '">' . $episode_counter . '</a></li>';
                    $episode_counter++;
                }

                // If no episode is provided, use the first episode
                if (!isset($_GET['episode_id']) && $firstEpisode !== null) {
                    $_GET['episode_id'] = $firstEpisode['episode_id'];
                }
                ?>
            </ul>
        </div>

        <!-- Main Player Section -->
        <div class="main-player">
            <?php
            // Database connection
            include 'dbconnect.php';

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Get episode number from the URL
            $episode_id = isset($_GET['episode_id']) ? intval($_GET['episode_id']) : 1;

            // Fetch episode details from the database
            $sql = "SELECT e.episode_id, a.anime_name, a.anime_image, e.episode_url
                    FROM episodes e 
                    JOIN anime a ON e.anime_id = a.anime_id 
                    WHERE e.episode_id = ? AND e.anime_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $episode_id, $anime_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $episodeDetails = $result->fetch_assoc();

            if ($episodeDetails): ?>
                <!-- Embed video using iframe -->
                <iframe src="https://drive.google.com/file/d/<?php echo htmlspecialchars($episodeDetails['episode_url']); ?>/preview" width="100%" height="600" allow="autoplay" class="video-player" allowfullscreen></iframe>
            <?php else: ?>
                <p>No video found for this episode.</p>
            <?php endif; ?>
        </div> <!-- Main Player Section ends -->

        <!-- Anime Information Section -->
        <div class="anime-info">
            <?php if (isset($episodeDetails['anime_image'])): ?>
                <img src="../assets/thumbnails/<?php echo $episodeDetails['anime_image']; ?>" alt="anime_image" class="thumbnail">
            <?php else: ?>
                <p>No image available for this anime.</p>
            <?php endif; ?>
            <h2><?php echo isset($episodeDetails['anime_name']) ? $episodeDetails['anime_name'] : 'Unknown Anime'; ?></h2>
            <p>Current Episode: <?php echo $current_episode_number; ?></p>
            <form method="post" action="like_dislike.php">
                <button name="like" value="<?php echo $episode_id; ?>">Like</button>
                <button name="dislike" value="<?php echo $episode_id; ?>">Dislike</button>
            </form>
        </div> <!-- Anime Information Section ends -->
    </div> <!-- Player Container ends -->
</body>
</html>