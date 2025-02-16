<?php
// Include database connection file
include_once("../includes/dbconnect.php");

// Check if episode_id is provided
if (isset($_GET['episode_id']) && is_numeric($_GET['episode_id'])) {
    $episode_id = $_GET['episode_id'];

    // Fetch episode data based on ID
    $result = mysqli_query($conn, "SELECT * FROM episodes WHERE episode_id=$episode_id");

    if ($result && mysqli_num_rows($result) > 0) {
        $episode = mysqli_fetch_array($result);
        $episode_url = $episode['episode_url'];
    } else {
        echo "<script>alert('Episode not found.');</script>";
        echo "<script>window.location.href = 'episodes.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid episode ID.');</script>";
    echo "<script>window.location.href = 'episodes.php';</script>";
    exit();
}

if (isset($_POST['update'])) {
    $episode_id = $_POST['episode_id'];
    $episode_url = $_POST['episode_url'];

    $sql = "UPDATE episodes SET episode_url='$episode_url' WHERE episode_id=$episode_id";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Episode updated successfully!');</script>";
        echo "<script>window.location.href = 'episodes.php';</script>";
    } else {
        echo "<script>alert('Error updating episode: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Episode</title>
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

        .form-container input[type="text"] {
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
        <h2>Update Episode</h2>
        <form action="update_episode.php" method="post">
            <input type="hidden" name="episode_id" value="<?php echo $episode_id; ?>">
            <input type="text" name="episode_url" placeholder="Episode URL" value="<?php echo $episode_url; ?>" required>
            <button type="submit" name="update">Update Episode</button>
        </form>
    </div>
</body>
</html>