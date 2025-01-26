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

    // Display the results
    if ($result->num_rows > 0) {
        echo "<h2>Search Results:</h2>";
        echo "<div class='anime-container'>";
        while ($row = $result->fetch_assoc()) {
            echo "<a href='../pages/player.php?id=" . htmlspecialchars(urlencode($row['anime_id'])) . "' class='anime-box'>";
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
