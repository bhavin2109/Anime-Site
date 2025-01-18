<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['anime_id']) && isset($data['anime_name']) && isset($data['anime_image'])) {
        // Fetch the current trending anime from the session
        $currentTrendingAnime = isset($_SESSION['trending_anime']) ? $_SESSION['trending_anime'] : [];

        // Create the new trending anime entry
        $newTrendingAnime = [
            'anime_id' => $data['anime_id'],
            'anime_name' => $data['anime_name'],
            'anime_image' => $data['anime_image']
        ];

        // Check if the new anime is already in the trending list
        $animeExists = false;
        foreach ($currentTrendingAnime as $anime) {
            if ($anime['anime_id'] == $newTrendingAnime['anime_id']) {
                $animeExists = true;
                break;
            }
        }

        if (!$animeExists) {
            // Add the new trending anime to the beginning
            array_unshift($currentTrendingAnime, $newTrendingAnime);

            // Ensure only 10 anime are in the session
            if (count($currentTrendingAnime) > 10) {
                array_pop($currentTrendingAnime);
            }
        }

        // Update the session with the new trending anime list
        $_SESSION['trending_anime'] = $currentTrendingAnime;

        // Debug statement
        error_log('Trending anime updated: ' . print_r($_SESSION['trending_anime'], true));

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>