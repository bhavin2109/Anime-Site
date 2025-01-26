<?php
session_start();
include '../includes/dbconnect.php';

// Fetch Slider Images
$sliderQuery = "SELECT * FROM slider ORDER BY RAND() LIMIT 7";
$sliderResult = mysqli_query($conn, $sliderQuery);
if (!$sliderResult) {
    error_log('Slider query failed: ' . mysqli_error($conn));
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

        .slider {
            position: relative;
            width: 100%;
            max-width: 800px;
            margin: auto;
            overflow: hidden;
            border-radius: 10px;
        }

        .slides {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .slides img {
            width: 100%;
            border-radius: 10px;
        }

        .navigation {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
        }

        .navigation button {
            background-color: rgba(0, 0, 0, 0.5);
            border: none;
            color: white;
            padding: 10px;
            cursor: pointer;
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
    <h2>Slider Images</h2>
    <div class="slider">
        <div class="slides">
            <?php if (mysqli_num_rows($sliderResult) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($sliderResult)): ?>
                    <img src="../assets/slider/<?php echo htmlspecialchars($row['slider_image']); ?>" alt="<?php echo htmlspecialchars($row['slider_image']); ?>">
                <?php endwhile; ?>
            <?php else: ?>
                <p>No slider images found.</p>
            <?php endif; ?>
        </div>
        <div class="navigation">
            <button onclick="prevSlide()">&#10094;</button>
            <button onclick="nextSlide()">&#10095;</button>
        </div>
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

<script>
    let slideIndex = 0;
    const slides = document.querySelector('.slides');
    const totalSlides = slides.children.length;

    function showSlide(index) {
        slideIndex = (index + totalSlides) % totalSlides;
        slides.style.transform = `translateX(-${slideIndex * 100}%)`;
    }

    function nextSlide() {
        showSlide(slideIndex + 1);
    }

    function prevSlide() {
        showSlide(slideIndex - 1);
    }

    setInterval(nextSlide, 2000);
</script>

</body>
</html>
<?php
$conn->close();
?>