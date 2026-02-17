<?php
session_start();
include 'dbconnect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/logo.ico">
    <title>Search Results — Anime Streaming</title>
    <link rel="stylesheet" href="../css/shared_styles.css">
    <link rel="stylesheet" href="../css/pages/search.css">
</head>
<body>
    <?php include 'header_shared.php'; ?>
    <div class="content reveal">
        <?php
if (isset($_GET['query'])) {
    $searchQuery = trim($_GET['query']);
    if (!empty($searchQuery)) {
        $sql = "SELECT * FROM anime WHERE anime_name LIKE ?";
        $searchPattern = "%" . $searchQuery . "%";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $searchPattern);
        $stmt->execute();
        $result = $stmt->get_result();
        echo "<h2 class='section-title'>Results for: \"" . htmlspecialchars($searchQuery) . "\"</h2>";
        if ($result->num_rows > 0) {
            echo '<div class="anime-container stagger-children">';
            while ($row = $result->fetch_assoc()) {
                echo '<a href="player.php?anime_id=' . htmlspecialchars($row['anime_id']) . '&episode=1" class="anime-box">';
                echo '<img src="../assets/thumbnails/' . htmlspecialchars($row['anime_image']) . '" alt="' . htmlspecialchars($row['anime_name']) . '">';
                echo '<h3>' . htmlspecialchars($row['anime_name']) . '</h3>';
                echo '</a>';
            }
            echo '</div>';
        }
        else {
            echo '<p class="no-results">No results found for "' . htmlspecialchars($searchQuery) . '"</p>';
        }
        $stmt->close();
    }
    else {
        echo '<p class="no-results">Please enter a search term.</p>';
    }
}
else {
    echo '<p class="no-results">No search query provided.</p>';
}
?>
    </div>
    <?php include '../includes/footer_shared.php'; ?>
</body>
</html>
<?php $conn->close(); ?>