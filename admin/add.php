<?php
include '../includes/dbconnect.php';

// Fetch anime list from the database
$anime_list = [];
$sql = "SELECT anime_id, anime_name FROM anime";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $anime_list[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["anime_name"])) {
        $anime_name = $_POST["anime_name"];
        $anime_image = $_FILES["anime_image"]["name"];
        $anime_type = $_POST["anime_type"];
        $genre = $_POST["genre"];
        $target_dir = "../assets/thumbnails/";
        $target_file = $target_dir . basename($anime_image);

        // Check if anime already exists
        $sql = "SELECT * FROM anime WHERE anime_name = '$anime_name'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('Anime already exists!');</script>";
        } else {
            if (move_uploaded_file($_FILES["anime_image"]["tmp_name"], $target_file)) {
                $sql = "INSERT INTO anime (anime_name, anime_image, anime_type, genre) VALUES ('$anime_name', '$anime_image', '$anime_type', '$genre')";
                if (mysqli_query($conn, $sql)) {
                    echo "<script>alert('Anime added successfully!');</script>";
                    echo "<script>window.location.href = 'add.php';</script>";
                } else {
                    echo "<script>alert('Error adding anime: " . mysqli_error($conn) . "');</script>";
                }
            } else {
                echo "<script>alert('Error uploading anime image.');</script>";
            }
        }
    } elseif (isset($_POST["slider_anime_id"])) {
        $slider_anime_id = $_POST["slider_anime_id"];
        $slider_image = $_FILES["slider_image"]["name"];
        $target_dir = "../assets/slider/";
        $target_file = $target_dir . basename($slider_image);

        // Fetch the number of episodes for the selected anime
        $sql = "SELECT episodes FROM anime WHERE anime_id = '$slider_anime_id'";
        $result = mysqli_query($conn, $sql);
        $episodes = 0;
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $episodes = $row['episodes'];
        }

        if (move_uploaded_file($_FILES["slider_image"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO slider (anime_id, slider_image, episodes) VALUES ('$slider_anime_id', '$slider_image', '$episodes')";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Slider added successfully!');</script>";
                echo "<script>window.location.href = 'dashboard.php';</script>";
            } else {
                echo "<script>alert('Error adding slider: " . mysqli_error($conn) . "');</script>";
            }
        } else {
            echo "<script>alert('Error uploading slider image.');</script>";
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
            width: 500px;
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
            <select name="anime_type" class="input-box" required>
                <option value="">Select Anime Type</option>
                <option value="TV">TV</option>
                <option value="Movie">Movie</option>
                <option value="OVA">OVA</option>
                <option value="ONA">ONA</option>
                <option value="Special">Special</option>
            </select>
            <select name="genre" class="input-box" required>
                <option value="">Select Genre</option>
                <option value="Action">Action</option>
                <option value="Adventure">Adventure</option>
                <option value="Comedy">Comedy</option>
                <option value="Drama">Drama</option>
                <option value="Fantasy">Fantasy</option>
                <option value="Horror">Horror</option>
                <option value="Isekai">Isekai</option>
                <option value="Mecha">Mecha</option>
                <option value="Music">Music</option>
                <option value="Mystery">Mystery</option>
                <option value="Psychological">Psychological</option>
                <option value="Romance">Romance</option>
                <option value="Sci-Fi">Sci-Fi</option>
                <option value="Seinen">Seinen</option>
                <option value="Shounen">Shounen</option>
                <option value="Slice of Life">Slice of Life</option>
                <option value="Sports">Sports</option>
                <option value="Supernatural">Supernatural</option>
                <option value="Thriller">Thriller</option>
            </select>
            <button type="submit">Add Anime</button>
        </form>
    </div>
</body>
</html>
