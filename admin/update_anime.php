<?php
include '../pages/dbconnect.php';

if (isset($_GET['id'])) {
    $anime_id = $_GET['id'];

    // Fetch anime details
    $query = "SELECT * FROM anime WHERE anime_id = $anime_id";
    $result = mysqli_query($conn, $query);
    $anime = mysqli_fetch_assoc($result);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $anime_name = $_POST["anime_name"];
    $anime_image = $_FILES["anime_image"]["name"];
    $target_dir = "../assets/thumbnails/";
    $target_file = $target_dir . basename($anime_image);

    if (move_uploaded_file($_FILES["anime_image"]["tmp_name"], $target_file)) {
        $sql = "UPDATE anime SET anime_name = '$anime_name', anime_image = '$anime_image' WHERE anime_id = $anime_id";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Anime updated successfully!');</script>";
            echo "<script>window.location.href = 'dashboard.php';</script>";
        } else {
            echo "<script>alert('Error updating anime: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Error uploading anime image.');</script>";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Anime</title>
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
        <h2>Update Anime</h2>
        <form action="update_anime.php?id=<?php echo $anime_id; ?>" method="post" enctype="multipart/form-data">
            <input type="text" name="anime_name" value="<?php echo $anime['anime_name']; ?>" placeholder="Anime Name" required>
            <input type="file" name="anime_image" required>
            <button type="submit">Update Anime</button>
        </form>
    </div>
</body>
</html>