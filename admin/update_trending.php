<?php
include '../pages/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $anime_identifier = $_POST["anime_identifier"];
    $identifier_type = $_POST["identifier_type"];

    // Check if anime exists
    if ($identifier_type == "id") {
        $query = "SELECT * FROM anime WHERE anime_id = '$anime_identifier'";
    } else {
        $query = "SELECT * FROM anime WHERE anime_name = '$anime_identifier'";
    }
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $anime = mysqli_fetch_assoc($result);
        $anime_id = $anime['anime_id'];
        $anime_name = $anime['anime_name'];
        $anime_image = $anime['anime_image'];

        // Store trending anime in session variables
        $_SESSION['trending_anime_id'] = $anime_id;
        $_SESSION['trending_anime_name'] = $anime_name;
        $_SESSION['trending_anime_image'] = $anime_image;

        echo "<script>alert('Trending anime updated successfully!');</script>";
        echo "<script>window.location.href = 'dashboard.php';</script>";
    } else {
        echo "<script>alert('Anime not found.');</script>";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Trending Anime</title>
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
        .form-container select {
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
        <h2>Update Trending Anime</h2>
        <form action="update_trending.php" method="post">
            <select name="identifier_type" required>
                <option value="id">Anime ID</option>
                <option value="name">Anime Name</option>
            </select>
            <input type="text" name="anime_identifier" placeholder="Anime ID or Name" required>
            <button type="submit">Update Trending Anime</button>
        </form>
    </div>
</body>
</html>