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
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
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
            height: 300px;
            width: 200px;
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
<?php 
include '../pages/dbconnect.php';

// Fetch Highlight Video
$highlightQuery = "SELECT * FROM highlight_videos";
$highlightResult = mysqli_query($conn, $highlightQuery);

// Fetch Trending Anime
$trendingAnimeQuery = "SELECT * FROM anime LIMIT 5";
$trendingAnimeResult = mysqli_query($conn, $trendingAnimeQuery);
if (!$trendingAnimeResult) {
    die("Query Failed: " . mysqli_error($conn));
}

?>

<div class="dashboard-container">
    <h2>Highlight Video</h2>
    <div class="box">
        <?php if (mysqli_num_rows($highlightResult) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($highlightResult)): ?>
                <div class="box-item">
                    <h3><?php echo $row['video_name']; ?></h3>
                    <p>ID: <?php echo $row['video_id']; ?></p>
                    <p>File ID: <?php echo $row['video_file']; ?></p>
                    <button onclick="location.href='update_highlight.php?id=<?php echo $row['video_id']; ?>'">Update</button>
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
        <?php while ($row = mysqli_fetch_assoc($trendingAnimeResult)): ?>
            <div class="box-item">
                <img src="../assets/thumbnails/<?php echo $row['anime_image']; ?>" alt="<?php echo $row['anime_name']; ?>">
                <h3><?php echo $row['anime_name']; ?></h3>
                <p>ID: <?php echo $row['anime_id']; ?></p>
                <button onclick="location.href='update_trending.php?id=<?php echo $row['anime_id']; ?>'">Update</button>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</div>

</body>
</html>
