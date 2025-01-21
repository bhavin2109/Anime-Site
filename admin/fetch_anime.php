<?php
include '../pages/dbconnect.php';

if (isset($_GET['identifier_type']) && isset($_GET['anime_identifier'])) {
    $identifierType = $_GET['identifier_type'];
    $animeIdentifier = $_GET['anime_identifier'];

    if ($identifierType == "id") {
        $query = "SELECT * FROM anime WHERE anime_id = ?";
    } else {
        $query = "SELECT * FROM anime WHERE anime_name = ?";
    }

    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $animeIdentifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $anime = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'anime_id' => $anime['anime_id'],
            'anime_name' => $anime['anime_name'],
            'anime_image' => $anime['anime_image']
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}

mysqli_close($conn);
?>