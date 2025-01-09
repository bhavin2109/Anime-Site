<?php
include '../pages/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $movie_name = $_POST["movie_name"];
    $movie_image = $_FILES["movie_image"]["name"];
    $target_dir = "../assets/thumbnails/";
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Movie</title>
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
            justify-content: center;
            align-items: center;
            overflow: hidden;
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
            justify-content: center;
        }

        .form-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .form-container input[type="text"],
        .form-container input[type="file"] {
            width: 95%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 10px;
        }

        .form-container button {
            width: 95%;
            padding: 10px;
            margin-top: 10px;
            align-self: center;
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
        <h2>Add Movie</h2>
        <form action="add_movie.php" method="post" enctype="multipart/form-data">
            <input type="text" name="movie_name" placeholder="Movie Name" required>
            <input type="file" name="movie_image" required>
            <button type="submit">Add Movie</button>
        </form>
    </div>
</body>
</html>