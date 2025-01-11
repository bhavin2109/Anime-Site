<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anime Player</title>
    <style>
        /* Basic styling for the anime player page */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Make the page take full height */
html, body {
    height: 100%;
    font-family: Arial, sans-serif;
    overflow: hidden; /* Hide scrollbars */
}

.player-container {
    display: flex;
    height: 100%;
    justify-content: space-between;
    padding: 1px; /* Increased padding */
    overflow: hidden; /* Prevent scrollbars */
}

.sidebar {
    width: 300px; /* Made sidebar wider */
    background-color: #bebaba;
    padding: 20px;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    height: 100%; /* Full height */
    overflow-y: auto; /* Sidebar content scrolls if necessary */
}

.episode-list {
    list-style: none;
    padding: 0;
}

.episode-list li {
    margin-bottom: 15px; /* Spaced out the episode links */
    margin-top: 8px;
    background-color: rgb(168, 169, 169);
    padding: 10px;
    border-radius: 5px;
    transition: 0.3s;
}

.episode-list li a {
    text-decoration: none;
    color: #333;
    font-size: 18px; /* Increased font size */
}
.episode-list li:hover {
    background-color: #939292;
}

.main-player {
    flex-grow: 1;
    text-align: center;
    height: 100%;
    overflow: hidden; /* Prevent horizontal scrolling */
}

.video-player {
    width: 100%;
    height: 65vh; /* Increased player height */
    border: none;
}

.anime-info {
    width: 350px; /* Made anime info section wider */
    background-color: #a1a1a1;
    padding: 25px;
    text-align: center;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    height: 100%; /* Full height */
    overflow-y: auto; /* Allow vertical scrolling if the content is too long */
}

.anime-info img {
    width: 150px;
    height: 200px;
    margin-bottom: 20px; /* Increased margin */
}

.anime-info h2 {
    font-size: 24px; /* Increased title font size */
    margin: 15px 0;
}

.anime-info p {
    font-size: 16px; /* Increased font size */
    color: #555;
    margin-bottom: 15px; /* Added spacing between text */
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
    font-size: 16px; /* Increased button text size */
    border-radius: 5px; /* Rounded corners */
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
    <?php include '../includes/header.php'; ?>
    <div class="player-container">
        <!-- Sidebar for Episodes -->
        <div class="sidebar">
            <h3>List of Episodes</h3>
            <ul class="episode-list">
                <?php
                include 'dbconnect.php';
                // Fetch all episodes for the anime
                $anime_id = isset($_GET['anime_id']) ? intval($_GET['anime_id']): $anime_id; // Default to anime_id 1 if not provided
                $episodes_query = "SELECT episode_id, episode_title FROM episodes WHERE anime_id = ?";
                $stmt = $conn->prepare($episodes_query);
                $stmt->bind_param("i", $anime_id);
                $stmt->execute();
                $episodes_result = $stmt->get_result();

                while ($episode = $episodes_result->fetch_assoc()) {
                    echo '<li><a href="?anime_id=' . $anime_id . '&episode_id=' . $episode['episode_id'] . '">' . $episode['episode_id'] . '. ' . $episode['episode_title'] . '</a></li>';
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
            $episode = isset($_GET['episode_id']) ? intval($_GET['episode_id']) : 1;

            // Fetch episode details from the database
            $sql = "SELECT e.episode_id, a.anime_name, a.anime_image, e.episode_url
                    FROM episodes e 
                    JOIN anime a ON e.anime_id = a.anime_id 
                    WHERE e.episode_id = ? AND e.anime_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $episode,$anime_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $episodeDetails = $result->fetch_assoc();

            if ($episodeDetails): ?>
                <!-- Embed video using iframe -->
                <iframe src="https://drive.google.com/file/d/<?php echo htmlspecialchars($episodeDetails['episode_url']); ?>/preview" width="100%" height="600" allow="autoplay" class="video-player"></iframe>
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
            <h2><?php echo $episodeDetails['anime_name']; ?></h2>
            <p>Current Episode: <?php echo $episode; ?></p>
            <form method="post" action="like_dislike.php">
                <button name="like" value="<?php echo $episode; ?>">Like</button>
                <button name="dislike" value="<?php echo $episode; ?>">Dislike</button>
            </form>
        </div> <!-- Anime Information Section ends -->
    </div> <!-- Player Container ends -->
</body>
</html>
