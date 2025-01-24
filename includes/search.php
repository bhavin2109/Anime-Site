<?php
// Database connection
include_once './pages/dbconnect.php';

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search query from the URL
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if (!empty($query)) {
    // Prepare the SQL query
    $sql = "SELECT * FROM anime WHERE name LIKE ? OR genre LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $query . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Display the results
    if ($result->num_rows > 0) {
        echo "<h2>Search Results:</h2>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li><strong>" . htmlspecialchars($row['anime_name']) . "</strong> - " . htmlspecialchars($row['genre']) . "<br>" . htmlspecialchars($row['description']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No anime found for: " . htmlspecialchars($query) . "</p>";
    }

    $stmt->close();
} else {
    echo "<p>Please enter a search term in the search box.</p>";
}

$conn->close();
?>
