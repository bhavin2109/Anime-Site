<?php
include '../pages/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["anime_name"])) {
        $anime_name = $_POST["anime_name"];
        $anime_image = $_FILES["anime_image"]["name"];
        $anime_type = $_POST["anime_type"];
        $genre = $_POST["genre"];
        $target_dir = "../assets/thumbnails/";
        $target_file = $target_dir . basename($anime_image);

        if (move_uploaded_file($_FILES["anime_image"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO anime (anime_name, anime_image, anime_type, genre) VALUES ('$anime_name', '$anime_image', '$anime_type', '$genre')";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Anime added successfully!');</script>";
                echo "<script>window.location.href = 'dashboard.php';</script>";
            } else {
                echo "<script>alert('Error adding anime: " . mysqli_error($conn) . "');</script>";
            }
        } else {
            echo "<script>alert('Error uploading anime image.');</script>";
        }
    } elseif (isset($_POST["movie_name"])) {
        $movie_name = $_POST["movie_name"];
        $movie_image = $_FILES["movie_image"]["name"];
        $anime_type = $_POST["anime_type"];
        $genre = $_POST["genre"];
        $target_dir = "../assets/thumbnails/";
        $target_file = $target_dir . basename($movie_image);

        if (move_uploaded_file($_FILES["movie_image"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO anime (anime_name, anime_image, anime_type, genre) VALUES ('$movie_name', '$movie_image', '$anime_type', '$genre')";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Movie added successfully!');</script>";
                echo "<script>window.location.href = 'anime.php';</script>";
            } else {
                echo "<script>alert('Error adding movie: " . mysqli_error($conn) . "');</script>";
            }
        } else {
            echo "<script>alert('Error uploading movie image.');</script>";
        }
    }
}

mysqli_close($conn);
?>

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
            height: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .form-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .form-container .input-box {
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
        <h2>Add Anime</h2>
        <form action="add.php" method="post" enctype="multipart/form-data">
            <input type="text" name="anime_name" placeholder="Anime Name" class="input-box" required>
            <input type="file" name="anime_image" class="input-box" required>
            <input type="text" name="anime_type" placeholder="Anime Type" class="input-box" required>
            <input type="text" name="genre" placeholder="Genre" class="input-box" required>
            <button type="submit">Add Anime</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Add Movie</h2>
        <form action="add.php" method="post" enctype="multipart/form-data">
            <input type="text" name="movie_name" placeholder="Movie Name" class="input-box" required>
            <input type="file" name="movie_image" class="input-box" required>
            <input type="text" name="anime_type" placeholder="Anime Type" class="input-box" required>
            <input type="text" name="genre" placeholder="Genre" class="input-box" required>
            <button type="submit">Add Movie</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Add Episode</h2>
        <form action="add_episode.php" method="post">
            <select name="identifier_type" class="input-box" required>
                <option value="id">Anime ID</option>
                <option value="name">Anime Name</option>
            </select>
            <input type="text" name="anime_identifier" placeholder="Anime ID or Name" class="input-box" required>
            <input type="text" name="episode_name" placeholder="Episode Name" class="input-box" required>
            <input type="text" name="episode_link" placeholder="Google Drive Link" class="input-box" required>
            <button type="submit">Add Episode</button>
        </form>
    </div>
</body>
</html>