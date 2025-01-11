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
    padding: 40px; /* Increased padding */
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
    height: 600px; /* Increased player height */
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
    width: 100%;
    height: auto;
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
    <div class="player-container">
        <!-- Sidebar for Episodes -->
        <div class="sidebar">
            <h3>List of Episodes</h3>
            <ul class="episode-list">
                <li><a href="?episode=1">1. Kill the King</a></li>
                <li><a href="?episode=2">2. The Dark Arm</a></li>
                <li><a href="?episode=3">3. The Betrayer</a></li>
                <!-- Add more episodes here -->
            </ul>
        </div>

        <!-- Main Player Section -->
        <div class="main-player">
            <?php
            // Get episode number from the URL
            $episode = isset($_GET['episode']) ? $_GET['episode'] : 1;
            $fileID = ""; // Default File ID for Google Drive

            // Define a simple mapping for episodes to file IDs (You should replace these with actual IDs)
            $fileIDs = [
                1 => 'your_google_drive_file_id_for_episode_1',
                2 => 'your_google_drive_file_id_for_episode_2',
                3 => 'your_google_drive_file_id_for_episode_3',
                // Add more episodes and their corresponding file IDs
            ];

            // Set the file ID based on the selected episode
            if (isset($fileIDs[$episode])) {
                $fileID = $fileIDs[$episode];
            }

            if ($fileID): ?>
                <!-- Embed video using iframe -->
                <iframe src="https://drive.google.com/file/d/<?php echo $fileID; ?>/preview" width="640" height="480" allow="autoplay" class="video-player"></iframe>
            <?php else: ?>
                <p>No video found for this episode.</p>
            <?php endif; ?>
        </div> <!-- Main Player Section ends -->

        <!-- Anime Information Section -->
        <div class="anime-info">
            <img src="assets/thumbnails/episode<?php echo $episode; ?>.jpg" alt="Episode Thumbnail" class="thumbnail">
            <h2>Bleach: Thousand-Year Blood War</h2>
            <p>Current Episode: <?php echo $episode; ?></p>
            <p>This is the synopsis of the current episode.</p>
            <form method="post" action="like_dislike.php">
                <button name="like" value="<?php echo $episode; ?>">Like</button>
                <button name="dislike" value="<?php echo $episode; ?>">Dislike</button>
            </form>
        </div> <!-- Anime Information Section ends -->
    </div> <!-- Player Container ends -->
</body>
</html>
