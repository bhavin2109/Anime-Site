<?php
session_start();
include '../pages/dbconnect.php';

// Fetch Highlight Video
$highlightQuery = "SELECT * FROM highlight_videos";
$highlightResult = mysqli_query($conn, $highlightQuery);
if (!$highlightResult) {
    error_log('Highlight query failed: ' . mysqli_error($conn));
}

// Fetch Trending Anime from the database
$trendingAnimeQuery = "SELECT * FROM anime ORDER BY RAND() LIMIT 10";
$trendingAnimeResult = mysqli_query($conn, $trendingAnimeQuery);
if (!$trendingAnimeResult) {
    error_log('Query Failed: ' . mysqli_error($conn));
    die("Query Failed: " . mysqli_error($conn));
}
$trendingAnime = [];
while ($row = mysqli_fetch_assoc($trendingAnimeResult)) {
    $trendingAnime[] = [
        'anime_id' => $row['anime_id'],
        'anime_name' => $row['anime_name'],
        'anime_image' => $row['anime_image']
    ];
}

// Debug statement
error_log('Trending anime fetched from database: ' . print_r($trendingAnime, true));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: rgb(150, 150, 150);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 20px;
        }

        .dashboard-container {
            width: 80%;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .box {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            justify-content: center;
        }

        .box-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px;
            padding: 10px;
            width: 200px;
            text-align: center;
        }

        .box-item img {
            border-radius: 10px;
            height: 350px;
            width: 200px;
            object-fit: cover;
        }

        .box-item h3 {
            margin: 10px 0;
        }

        .box-item button {
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .box-item button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <h2>Highlight Video</h2>
    <div class="box">
        <?php if (mysqli_num_rows($highlightResult) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($highlightResult)): ?>
                <div class="box-item">
                    <h3><?php echo htmlspecialchars($row['video_name']); ?></h3>
                    <p>ID: <?php echo htmlspecialchars($row['video_id']); ?></p>
                    <p>File ID: <?php echo htmlspecialchars($row['video_file']); ?></p>
                    <button onclick="location.href='update_highlight.php?id=<?php echo htmlspecialchars($row['video_id']); ?>'">Update</button>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No highlight videos found.</p>
        <?php endif; ?>
    </div>
</div>

<div class="dashboard-container">
    <h2>Trending Anime</h2>
    <div class="box">
        <?php if (!empty($trendingAnime)): ?>
            <?php foreach ($trendingAnime as $anime): ?>
                <div class="box-item">
                    <img src="../assets/thumbnails/<?php echo htmlspecialchars($anime['anime_image']); ?>" alt="<?php echo htmlspecialchars($anime['anime_name']); ?>">
                    <h3><?php echo htmlspecialchars($anime['anime_name']); ?></h3>
                    <p>ID: <?php echo htmlspecialchars($anime['anime_id']); ?></p>
                    <button onclick="location.href='update_trending.php'">Update Trending Anime</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No trending anime found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
<?php
$conn->close();
?>