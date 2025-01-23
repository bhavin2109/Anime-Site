<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ./pages/login.php");
    exit();
}

// Include database connection
include './pages/dbconnect.php';

// Fetch Slider Images
$sliderQuery = "SELECT * FROM slider ORDER BY RAND() LIMIT 7";
$sliderResult = mysqli_query($conn, $sliderQuery);
if (!$sliderResult) {
    error_log('Slider query failed: ' . mysqli_error($conn));
}

// Fetch highlight images
$highlightImagesQuery = "SELECT * FROM slider ORDER BY RAND() LIMIT 7";
$highlightImagesResult = $conn->query($highlightImagesQuery);
$highlightImages = $highlightImagesResult->fetch_all(MYSQLI_ASSOC);

// Fetch trending anime randomly
$trendingAnimeQuery = "SELECT * FROM anime ORDER BY RAND() LIMIT 10";
$trendingAnimeResult = $conn->query($trendingAnimeQuery);

// Check if the query was successful
if (!$trendingAnimeResult) {
    error_log('Trending anime query failed: ' . mysqli_error($conn));
    $trendingAnime = [];
} else {
    $trendingAnime = [];
    while ($anime = $trendingAnimeResult->fetch_assoc()) {
        $trendingAnime[] = $anime;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bleach</title>
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
            color: #333;
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

        header {
            background: transparent;
            color: #fff;
            padding: 10px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .logo img {
            height: 50px;
        }

        .options a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .options a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .search-section input {
            padding: 5px;
            border: none;
            border-radius: 5px;
        }

        main {
            display: flex;
            flex-direction: column;
            margin-top: 5vh;
        }

        .slider-container {
            position: relative;
            justify-self: center;
            align-self: center;
            top: 0;
            width: 80%;
            height: 80vh;
            overflow: hidden;
            display: flex;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
        }

        .slider {
            display: flex;
            width: 100%;
            height: 100%;
            transition: transform 0.5s ease-in-out;
        }

        .slider-item {
            width: 100%;
            height: 100%;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .slider-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
            max-width: 100%;
            max-height: 100%;
            box-shadow: inset 0 0 10px 10px rgba(0, 0, 0, 0.5);
        }

        .slider-nav {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
            z-index: 10;
        }

        .slider-nav button {
            background: rgba(0, 0, 0, 0.1);
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 10%;
            transition: background 0.3s;
        }

        .slider-nav button:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                align-items: flex-start;
            }

            .options {
                margin-top: 10px;
            }

            .search-section {
                margin-top: 10px;
            }
        }
        .box-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 60px;
            justify-content: center;
            margin: 20px 0;
            padding: 0 20px;
        }

        .box-anime {
            width: 100%;
            text-align: center;
            text-decoration: none;
            color: #fff;
            transition: transform 0.3s;
            background: transparent;
            border-radius: 8px;
            overflow: hidden;
        }

        .box-anime:hover {
            transform: scale(1.02);
        }

        .box-anime img {
            width: 200px;
            height: 300px;
            border-radius: 8px;
        }

        .box-anime .anime_name {
            padding: 10px;
            font-size: 1.2rem;
        }

        .footer-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            justify-content: center;
            padding: 50px 150px;
            background-color: #333;
            color: #fff;
            height: auto;
        }

        .contact-us ul {
            list-style: none;
            padding: 0;
        }

        .contact-us ul li {
            margin: 10px 0;
            padding: 5px;
        }

        .contact-us ul li a {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .contact-us ul li a img {
            width: 24px;
            height: 24px;
            margin-right: 10px;
        }

        .feedback-container input {
            display: block;
            width: 100%;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            padding: 10px;
            box-sizing: border-box;
        }

        .feedback-container input[type="text"] {
            height: 150px;
        }

        .submit-btn {
            background-color: #fff;
            color: #333;
            border: none;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
            border-radius: 5px;
        }

        .submit-btn:hover {
            background-color: #ddd;
        }

        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                align-items: flex-start;
            }

            .options {
                margin-top: 10px;
            }

            .search-section {
                margin-top: 10px;
            }

            .footer-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <nav>
            <div class="logo">
                <img src="#" alt="Logo">
            </div>
            <div class="options">
                <a href="home.php">Home</a>
                <a href="./pages/explore.php">Explore</a>
                <a href="#">Category</a>
                <a href="./pages/profile.php">Profile</a>
            </div>
            <div class="search-section">
                <input type="search" name="searchbar" placeholder="Search Anime">
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <section class="slider-container">
            <div class="slider" id="slider">
                <?php foreach ($highlightImages as $image): ?>
                    <div class="slider-item">
                        <a href="./pages/player.php?anime_id=<?php echo htmlspecialchars($image['anime_id']); ?>&episode=1">
                            <img src="./assets/slider/<?php echo htmlspecialchars($image['slider_image']); ?>" alt="<?php echo htmlspecialchars($image['slider_image']); ?>">
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="slider-nav">
                <button id="prev">&#10094;</button>
                <button id="next">&#10095;</button>
            </div>
        </section>

        <section>
            <h2 style="text-align: center; margin: 50px 0; color:#fff;">Anime Suggestions</h2>
            <div class="box-container">
                <?php if (!empty($trendingAnime)): ?>
                    <?php foreach ($trendingAnime as $anime): ?>
                        <a href="./pages/player.php?anime_id=<?php echo htmlspecialchars($anime['anime_id']); ?>&episode=1" class="box-anime">
                            <img src="./assets/thumbnails/<?php echo htmlspecialchars($anime['anime_image']); ?>">
                            <div class="anime_name"><?php echo htmlspecialchars(isset($anime['anime_name']) ? $anime['anime_name'] : 'Unknown Title'); ?></div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No trending anime found.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <section class="footer-container">
            <div class="contact-us">
                <h2>Contact Us</h2>
                <div class="contact-us-container">
                    <ul type="none">
                        <li><a href="https://www.instagram.com/bleach_tbh?igsh=cTBmM20zM2M4OWFs"><img src="./assets/icons/instagram.png">Instagram</a></li>
                        <li><a href="#"><img src="./assets/icons/telegram.png">Telegram</a></li>
                        <li><a href="#"><img src="./assets/icons/twitter.png">X</a></li>
                        <li><a href="#"><img src="./assets/icons/facebook.png">Facebook</a></li>
                        <li><a href="#"><img src="./assets/icons/gmail.png">G-Mail</a></li>
                    </ul>
                </div>
            </div>

            <div class="feedback-form">
                <h2>Feedback</h2>
                <form action="" class="feedback-container" name="feedback-form" method="post">
                    <input type="email" name="emailid" class="email-feedback" placeholder="E-Mail">
                    <input type="text" name="feedback-text" class="text-feedback" placeholder="Feedback">
                    <input type="button" value="Submit" name="submitok" class="submit-btn">
                </form>
            </div>
        </section>
        <p style="height: 5vh; width: 100%; display: flex; align-items:center; justify-content:center; color:#fff;">&copy; Group No.1</p>
    </footer>

    <script>
        const slider = document.getElementById('slider');
        const prev = document.getElementById('prev');
        const next = document.getElementById('next');
        let currentIndex = 0;
        const items = slider.children;
        const totalItems = items.length;

        prev.addEventListener('click', () => {
            currentIndex = (currentIndex === 0) ? totalItems - 1 : currentIndex - 1;
            slider.style.transform = `translateX(-${currentIndex * 100}%)`;
        });

        next.addEventListener('click', () => {
            currentIndex = (currentIndex === totalItems - 1) ? 0 : currentIndex + 1;
            slider.style.transform = `translateX(-${currentIndex * 100}%)`;
        });

        setInterval(() => {
            next.click();
        }, 5000);
    </script>
</body>

</html>
<?php
$conn->close();
?>
