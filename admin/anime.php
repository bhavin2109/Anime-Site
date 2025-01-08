<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Anime</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .form-container input[type="text"],
        .form-container input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add Highlight Video</h2>
        <form action="anime.php" method="post">
            <input type="text" name="video_name" placeholder="Highlight Name" required>
            <input type="text" name="video_url" placeholder="Google Drive URL" required>
            <button type="submit">Add Highlight Video</button>
        </form>
    </div>
    <div class="form-container">
        <h2>Add Anime</h2>
        <form action="anime.php" method="post" enctype="multipart/form-data">
            <input type="text" name="anime_name" placeholder="Anime Name" required>
            <input type="file" name="anime_image" required>
            <button type="submit">Add Anime</button>
        </form>
    </div>
    <div class="form-container">
        <h2>Add Movie</h2>
        <form action="anime.php" method="post" enctype="multipart/form-data">
            <input type="text" name="movie_name" placeholder="Movie Name" required>
            <input type="file" name="movie_image" required>
            <button type="submit">Add Movie</button>
        </form>
    </div>
</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../pages/dbconnect.php';

    // Add Highlight Video
    if (isset($_POST["video_name"]) && isset($_POST["video_url"])) {
        $video_name = $_POST["video_name"];
        $video_url = $_POST["video_url"];

        $sql = "INSERT INTO highlight_videos (video_name, video_url) VALUES ('$video_name', '$video_url')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Highlight video added successfully!');</script>";
            echo "<script>window.location.href = 'dashboard.php';</script>";
        } else {
            echo "<script>alert('Error adding highlight video: " . mysqli_error($conn) . "');</script>";
        }
    }

    // Add Anime
    if (isset($_POST["anime_name"])) {
        $anime_name = $_POST["anime_name"];
        $anime_image = $_FILES["anime_image"]["name"];
        $target_dir = "../assets/thumbnails/";
        $target_file = $target_dir . basename($anime_image);

        if (move_uploaded_file($_FILES["anime_image"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO anime (anime_name, anime_image) VALUES ('$anime_name', '$anime_image')";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Anime added successfully!');</script>";
                echo "<script>window.location.href = 'dashboard.php';</script>";
            } else {
                echo "<script>alert('Error adding anime: " . mysqli_error($conn) . "');</script>";
            }
        } else {
            echo "<script>alert('Error uploading anime image.');</script>";
        }
    }

    // Add Movie
    if (isset($_POST["movie_name"])) {
        $movie_name = $_POST["movie_name"];
        $movie_image = $_FILES["movie_image"]["name"];
        $target_dir = "../assets/thumbnails/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($movie_image);

        if (move_uploaded_file($_FILES["movie_image"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO movies (movie_name, movie_image) VALUES ('$movie_name', '$movie_image')";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Movie added successfully!');</script>";
                echo "<script>window.location.href = 'dashboard.php';</script>";
            } else {
                echo "<script>alert('Error adding movie: " . mysqli_error($conn) . "');</script>";
            }
        } else {
            echo "<script>alert('Error uploading movie image.');</script>";
        }
    }

    mysqli_close($conn);
}
?>
