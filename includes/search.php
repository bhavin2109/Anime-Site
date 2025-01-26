<?php
// Database connection
include_once 'dbconnect.php';

$conn = new mysqli($server, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search query from the URL
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if (!empty($query)) {
    // Prepare the SQL query
    $sql = "SELECT * FROM anime WHERE anime_name LIKE ? OR genre LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $query . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
        .anime-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .anime-box {
            border-radius: 8px;
            padding: 10px;
            width: 200px;
            text-align: center;
            text-decoration: none;
            color: inherit;
        }

        .anime-box img {
            max-width: 100%;
            height: 250px;
            border-radius: 8px;
        }

        .anime-box h3 {
            font-size: 1.2em;
            margin: 10px 0 0;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="content">
    <?php
        
            // Display the results
            if ($result->num_rows > 0) {
                echo "<h2>Search Results:</h2>";
                echo "<div class='anime-container'>";
                while ($row = $result->fetch_assoc()) {
                    // Fetch the first episode for the anime
                    $firstEpisodeQuery = "SELECT episode_id FROM episodes WHERE anime_id = ? ORDER BY episode_id ASC LIMIT 1";
                    $firstEpisodeStmt = $conn->prepare($firstEpisodeQuery);
                    $firstEpisodeStmt->bind_param("i", $row['anime_id']);
                    $firstEpisodeStmt->execute();
                    $firstEpisodeResult = $firstEpisodeStmt->get_result();
                    $firstEpisode = $firstEpisodeResult->fetch_assoc();

                    $firstEpisodeId = isset($firstEpisode['episode_id']) ? $firstEpisode['episode_id'] : 1;

                    echo "<a href='player.php?anime_id=" . htmlspecialchars($row['anime_id']) . "&episode_id=" . htmlspecialchars($firstEpisodeId) . "' class='anime-box'>";
                    echo "<img src='../assets/thumbnails/" . htmlspecialchars($row['anime_image']) . "' alt='" . htmlspecialchars($row['anime_name']) . "'>";
                    echo "<h3>" . htmlspecialchars($row['anime_name']) . "</h3>";
                    echo "</a>";
                }
                echo "</div>";
            } else {
                echo "<p>No anime found for: " . htmlspecialchars($query) . "</p>";
            }

            $stmt->close();
        } else {
            echo "<p>Please enter a search term in the search box.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>