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
    $sql = "SELECT * FROM anime WHERE anime_name LIKE ? OR genre LIKE ? LIMIT 24";
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #000000, #1a1a1a, #333333, #000000);
            background-size: 300% 300%;
            animation: gradient-animation 4s ease infinite;
            display: flex;
            justify-content: center;
            align-items: center;
            height: auto;
            flex-direction: column;
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
        .anime-container {
            display: flex;
            justify-content: space-evenly;
            flex-wrap: wrap;
            gap: 30px;
        }

        .content {
            width: 95%;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            color: #fff;
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
            width: 180px;
            height: 280px;
            border-radius: 8px;
        }

        .anime-box h3 {
            font-size: 1.2em;
            margin: 10px 0 0;
            color: aliceblue;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="content">
    <?php
        
            // Display the results
            if ($result->num_rows > 0) {
                
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

    <?php include 'footer.php'; ?>
</body>
</html>