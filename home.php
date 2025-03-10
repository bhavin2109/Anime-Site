<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ./pages/login.php");
    exit();
}

// Include database connection
include './includes/dbconnect.php';



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
    <link rel="icon" type="image/png" href="./assets/logo.ico">

    <title>Home</title>
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
            background: rgba(0, 0, 0, 0.5);
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
            height: 30px
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

        .search-section {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }

        .search-section input {
            padding: 10px;
            border: none;
            border-radius: 5px 0 0 5px;
            background: transparent;
            transition: 0.3s ease-in-out;
        }

        .search-section input::placeholder {
            color: #fff;
        }

        .search-section input:focus {
            outline: none;
        }



        .search-section input:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .search-section input:focus::placeholder {
            color: transparent;
        }

        .search-section button {
            padding: 8px;
            border: none;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            background: transparent;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            position: relative;

        }

        .search-section button img {
            width: 20px;
            height: 20px;
        }

        .search-section button:hover {
            background-color: rgba(255, 255, 255, 0.1);
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
            top: -2vh;
            width: 70vw;
            height: 70vh;
            overflow: hidden;
            display: flex;
            border-radius: 10px;
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
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease-in-out;
        }

        .slider-item.active {
            opacity: 1;
        }

        .slider-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
            max-width: 100%;
            max-height: 100%;
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


        .genre-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            justify-content: center;
            margin: 20px 0;
            padding: 0 20px;
        }

        .genre-box {
            width: 100%;
            height: 120vh;
            background: linear-gradient(135deg, cyan, pink, blue);
            background-size: 300% 300%;
            animation: gradient-animation 15s ease infinite;
            border-radius: 10px;
            overflow-y: auto;
            scroll-behavior: smooth;
            scrollbar-width: none;
            padding: 20px;
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

        .genre-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .anime-grid {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .anime-item {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #333;
            transition: 0.3s;
            padding: 10px;
            border-radius: 5px;
            background: transparent;
        }

        .anime-item:hover {
            background-color: #e0e0e0;
        }

        .anime-item img {
            width: 100px;
            height: 150px;
            border-radius: 4px;
            margin-right: 20px;
        }

        .anime-details {
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
            height: 100%;
        }

        .anime-details .anime_name {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .anime-details .anime-info {
            font-size: 14px;
            color: #666;
        }

        .anime-info {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 10px;
        }

        .additional-section {
            margin: 20px 0;
            padding: 20px;
            width: 98%;
            justify-self: center;
            align-self: center;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .additional-section h2 {
            margin-bottom: 20px;
        }

        .additional-section p {
            margin-bottom: 10px;
        }

        .movie-container {
            display: grid;
            width: 100%;
            align-self: flex-start;
            justify-self: first baseline;
            gap: 20px;
            justify-content: center;
            margin: 20px 0;
            padding: 0 20px;
        }

        .movie-box {
            width: 100%;
            height: 50vh;
            display: flex;
            align-items: center;
            justify-content: space-evenly;
            background: linear-gradient(135deg, cyan, pink, blue);
            background-size: 300% 300%;
            animation: gradient-animation 15s ease infinite;
            border-radius: 10px;
            overflow-x: auto;
            overflow-y: hidden;
            scroll-behavior: smooth;
            scrollbar-width: none;
            padding: 20px;
        }

        .movie-grid {
            display: flex;
            flex-wrap: nowrap;
            gap: 30px;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .movie-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #333;
            transition: 0.3s;
            padding: 10px;
            border-radius: 5px;
            background: transparent;
        }

        .movie-item:hover {
            background-color: #e0e0e0;
        }

        .movie-item img {
            width: 180px;
            height: 270px;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .movie-details {
            text-align: center;
        }

        .movie_name {
            font-size: 16px;
            margin-bottom: 10px;
            text-align: center;
        }

        
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <nav>
            <div class="logo">
                <img src="./assets/logo.ico" alt="Logo">
            </div>
            <div class="options">
                <a href="home.php">Home</a>
                <a href="./pages/explore.php">Movies</a>
                <a href="./admin/admin.php">Admin</a>
                <a href="./pages/profile.php">Profile</a>
            </div>
            <div class="search-section">
                <input type="search" id="searchQuery" name="searchbar" placeholder="Search Anime">
                <button onclick="performSearch()"><img src="./assets/icons/search.png"></button>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <section class="additional-section">
            <h2>Welcome to Anime Streaming Site!</h2>
            <p>Discover the latest and greatest in anime. From trending series to classic movies, we have it all. Dive into your favorite genres and explore new adventures. Enjoy your stay!</p>
        </section>

        <h2 style="text-align: center; margin: 20px 0; color:#fff;">Anime</h2>
        <section class="genre-container">

            <?php

            // Define the genres you want to display
            $genres = ['Action', 'Shounen', 'Psychological', 'Seinen'];
            $animeByGenre = [];

            foreach ($genres as $genre) {
                $query = "
                    SELECT 
                        a.anime_id, 
                        a.anime_name, 
                        a.anime_image, 
                        a.anime_type, 
                        COUNT(e.episode_id) AS episode_count
                    FROM 
                        anime a
                    LEFT JOIN 
                        episodes e ON a.anime_id = e.anime_id
                    WHERE 
                        a.genre = ? and a.anime_type != 'Movie' AND e.episode_id IS NOT NULL
                    GROUP BY 
                        a.anime_id, a.anime_name, a.anime_image, a.anime_type
                    ORDER BY
                        RAND()
                    LIMIT 10
                ";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $genre);
                $stmt->execute();
                $result = $stmt->get_result();
                $animeByGenre[$genre] = $result->fetch_all(MYSQLI_ASSOC);
            }
            ?>

            <?php foreach ($genres as $genre): ?>

                <div class="genre-box">
                    <h2><?php echo htmlspecialchars($genre); ?></h2>
                    <div class="anime-grid">
                        <?php if (!empty($animeByGenre[$genre])): ?>
                            <?php foreach ($animeByGenre[$genre] as $anime): ?>
                                <a href="./includes/player.php?anime_id=<?php echo htmlspecialchars($anime['anime_id']); ?>&episode=1" class="anime-item">
                                    <img src="./assets/thumbnails/<?php echo htmlspecialchars($anime['anime_image']); ?>">
                                    <div class="anime-details">
                                        <div class="anime_name"><?php echo htmlspecialchars(isset($anime['anime_name']) ? $anime['anime_name'] : 'Unknown Title'); ?></div>
                                        <div class="anime-info">
                                            <span>Episodes: <?php echo htmlspecialchars(isset($anime['episode_count']) ? $anime['episode_count'] : 'N/A'); ?></span>
                                            <span>Type: <?php echo htmlspecialchars(isset($anime['anime_type']) ? $anime['anime_type'] : 'N/A'); ?></span>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No anime found in this genre.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>

        

            <h2 style="text-align: center; margin: 20px 0; color:#fff;">Movies</h2>

            <section class="movie-container">
                <?php
                // Fetch trending movies randomly
                $trendingMoviesQuery = "SELECT * FROM anime WHERE anime_type = 'Movie' ORDER BY RAND() LIMIT 10";
                $trendingMoviesResult = $conn->query($trendingMoviesQuery);

                // Check if the query was successful
                if (!$trendingMoviesResult) {
                    error_log('Trending movies query failed: ' . mysqli_error($conn));
                    $trendingMovies = [];
                } else {
                    $trendingMovies = [];
                    while ($movie = $trendingMoviesResult->fetch_assoc()) {
                        $trendingMovies[] = $movie;
                    }
                }
                ?>

                <div class="movie-box">
                    <div class="movie-grid">
                        <?php if (!empty($trendingMovies)): ?>
                            <?php foreach ($trendingMovies as $movie): ?>
                                <a href="./includes/player.php?anime_id=<?php echo htmlspecialchars($movie['anime_id']); ?>" class="movie-item">
                                    <img src="./assets/thumbnails/<?php echo htmlspecialchars($movie['anime_image']); ?>" alt="<?php echo htmlspecialchars($movie['anime_name']); ?>">
                                    <div class="movie-details">
                                        <div class="movie_name"><?php echo htmlspecialchars($movie['anime_name']); ?></div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No trending movies found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
    </main>

    <h2 style="text-align: center; margin: 20px 0; color:#fff;">Upcoming</h2>

    <section class="movie-container">
        <?php
        // Fetch upcoming anime (anime with zero episodes)
        $upcomingAnimeQuery = "SELECT * FROM anime WHERE  anime_id NOT IN (SELECT anime_id FROM episodes) ORDER BY RAND() LIMIT 10";
        $upcomingAnimeResult = $conn->query($upcomingAnimeQuery);

        // Check if the query was successful
        if (!$upcomingAnimeResult) {
            error_log('Upcoming anime query failed: ' . mysqli_error($conn));
            $upcomingAnime = [];
        } else {
            $upcomingAnime = [];
            while ($anime = $upcomingAnimeResult->fetch_assoc()) {
                $upcomingAnime[] = $anime;
            }
        }
        ?>

        <div class="movie-box">
            <div class="movie-grid">
                <?php if (!empty($upcomingAnime)): ?>
                    <?php foreach ($upcomingAnime as $anime): ?>
                        <a href="./includes/player.php?anime_id=<?php echo htmlspecialchars($anime['anime_id']); ?>" class="movie-item">
                            <img src="./assets/thumbnails/<?php echo htmlspecialchars($anime['anime_image']); ?>" alt="<?php echo htmlspecialchars($anime['anime_name']); ?>">
                            <div class="movie-details">
                                <div class="movie_name"><?php echo htmlspecialchars($anime['anime_name']); ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No upcoming anime found.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

   

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
        function performSearch() {
            const query = document.getElementById('searchQuery').value.trim();
            if (query) {
                window.location.href = `./includes/search.php?query=${encodeURIComponent(query)}`;
            } else {
                alert('Please enter a search term.');
            }
        }
    </script>
</body>

</html>
<?php
$conn->close();
?>