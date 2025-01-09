<?php
include '../pages/dbconnect.php';

if (isset($_GET['id'])) {
    $movie_id = $_GET['id'];

    // Fetch movie details
    $query = "SELECT * FROM movies WHERE movie_id = $movie_id";
    $result = mysqli_query($conn, $query);
    $movie = mysqli_fetch_assoc($result);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $movie_name = $_POST["movie_name"];
    $movie_image = $_FILES["movie_image"]["name"];
    $target_dir = "../assets/thumbnails/";
    $target_file = $target_dir . basename($movie_image);

    if (move_uploaded_file($_FILES["movie_image"]["tmp_name"], $target_file)) {
        $sql = "UPDATE movies SET movie_name = '$movie_name', movie_image = '$movie_image' WHERE movie_id = $movie_id";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Movie updated successfully!');</script>";
            echo "<script>window.location.href = 'dashboard.php';</script>";
        } else {
            echo "<script>alert('Error updating movie: " . mysqli_error($conn) . "');</script>";
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
    <title>Update Movie</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
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
        <h2>Update Movie</h2>
        <form action="update_movie.php?id=<?php echo $movie_id; ?>" method="post" enctype="multipart/form-data">
            <input type="text" name="movie_name" value="<?php echo $movie['movie_name']; ?>" placeholder="Movie Name" required>
            <input type="file" name="movie_image" required>
            <button type="submit">Update Movie</button>
        </form>
    </div>
</body>
</html>