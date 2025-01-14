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
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: rgba(31, 31, 31, 0.8);
            opacity: 0.8;
            box-shadow: inset 0 0 10px #000;
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
            transition: 0.3s;  
        }

        .options a:hover {
            background-color: rgba(101, 101, 101, 0.8);
        }

        .search-section input {
            padding: 5px;
        }

        .video-container {
            position: relative;
            top: -8vh;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 1vh;
        }

        video {
            object-fit: cover;
        }

        .video-home {
            width: 100%;
            height: 100%;
            border: none;
        }

        .box-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin: 20px 0;
        }

        .box-anime {
            width: 200px;
            margin: 10px;
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: 0.3s;
        }

        .box-anime:hover {
            transform: scale(1.02);
        }

        .box-anime img {
            width: 200px;
            height: 300px;
            border-radius: 4px;
        }

        .footer-container {
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            padding: 20px;
            background-color: #333;
            color: #fff;
            height: 60vh;
        }

        .contact-us ul {
            list-style: none;
            padding: 0;
        }

        .contact-us ul li {
            margin: 10px 0px;
            padding: 5px;
        }

        .contact-us ul li a {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .contact-us ul li img {
            width: 24px;
            height: 24px;
            margin-right: 10px;
        }

        .feedback-container input {
            display: block;
            width: 30vw;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            padding: 10px;
        }
        .feedback-container input[type="text"] {
            height: 150px;
        }

        .submit-btn {
            background-color: gray;
            color: black;
            border: none;
            padding: 10px;
            cursor: pointer;
            transition: 0.3s;
        }

        .submit-btn:hover {
            background-color: rgba(101, 101, 101, 0.8);
            color: white;
        }
    </style>
</head>

<body>
    <?php
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: ./pages/login.php");
        exit();
    }

    // Include database connection
    include './pages/dbconnect.php';

    // Fetch highlight video
    $highlightVideoQuery = "SELECT * FROM highlight_videos WHERE video_id = 1 LIMIT 1";
    $highlightVideoResult = $conn->query($highlightVideoQuery);
    $highlightVideo = $highlightVideoResult->fetch_assoc();

    // Fetch trending anime
    $trendingAnimeQuery = "SELECT * FROM anime LIMIT 10 ";
    $trendingAnimeResult = $conn->query($trendingAnimeQuery);

    ?>
    <!-- Header -->
    <header>
        <nav>
            <div class="logo">
                <img src="#" alt="Logo">
            </div>
            <div class="options">
                <a href="home.php">Home</a>
                <a href="#">Anime List</a>
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
        <section class="video-container">
            <?php if ($highlightVideo): ?>
                <video src="./assets/videos/<?php echo $highlightVideo['video_file']; ?>" class="video-home" autoplay muted loop></video>

            <?php endif; ?>
        </section>

        <section>
            <h2>Trending</h2>
            <div class="box-container">
                <?php while ($anime = $trendingAnimeResult->fetch_assoc()): ?>
                    <a href="./pages/player.php?anime_id=<?php echo $anime['anime_id']; ?>&episode=1" class="box-anime">

                        <img src="./assets/thumbnails/<?php echo $anime['anime_image']; ?>">
                        <div class="anime_name"><?php echo isset($anime['anime_name']) ? $anime['anime_name'] : 'Unknown Title'; ?></div>
                    </a>
                <?php endwhile; ?>
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
                        <li><a href="#"><img src="./assets/icons/instagram.png">Instagram</a></li>
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
        <p style=" height: 5vh; width: 100%; display: flex; align-items:center; justify-content:center;">&copy; Group No.1</p>
    </footer>
</body>

</html>
<?php
$conn->close();
?>