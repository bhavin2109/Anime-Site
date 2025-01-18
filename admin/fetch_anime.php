<?php
include '../pages/dbconnect.php';

if (isset($_GET['identifier_type']) && isset($_GET['anime_identifier'])) {
    $identifierType = $_GET['identifier_type'];
    $animeIdentifier = $_GET['anime_identifier'];

    if ($identifierType == "id") {
        $query = "SELECT * FROM anime WHERE anime_id = '$animeIdentifier'";
    } else {
        $query = "SELECT * FROM anime WHERE anime_name = '$animeIdentifier'";
    }

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $anime = mysqli_fetch_assoc($result);
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