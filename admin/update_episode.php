<?php
// Include database connection file
include_once("../includes/dbconnect.php");

// Fetch all episodes
$result = mysqli_query($conn, "SELECT * FROM episodes");

if (!$result || mysqli_num_rows($result) == 0) {
    echo "<script>alert('No episodes found.');</script>";
    echo "<script>window.location.href = 'episodes.php';</script>";
    exit();
}

if (isset($_POST['update'])) {
    $urls = explode(",", trim($_POST['episode_urls']));
    foreach ($urls as $index => $url) {
        $url = trim($url);
        if (!empty($url)) {
            $episode_id = $index + 1; // Assuming episode IDs are sequential starting from 1
            $sql = "UPDATE episodes SET episode_url='$url' WHERE episode_id=$episode_id";
            if (!mysqli_query($conn, $sql)) {
                echo "<script>alert('Error updating episode ID $episode_id: " . mysqli_error($conn) . "');</script>";
            }
        }
    }
    echo "<script>alert('Episodes updated successfully!');</script>";
    echo "<script>window.location.href = 'episodes.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Episodes</title>
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
            width: 500px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .form-container textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            height: 200px;
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
        <h2>Update Episodes</h2>
        <form action="update_episode.php" method="post">
            <textarea name="episode_urls" placeholder="Enter episode URLs, separated by commas" required></textarea>
            <button type="submit" name="update">Update Episodes</button>
        </form>
    </div>
</body>
</html>
