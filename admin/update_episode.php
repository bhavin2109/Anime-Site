<?php
// Include database connection file
include_once("../includes/dbconnect.php");

// Get episode ID from URL parameter
$episode_id = isset($_GET['episode_id']) ? intval($_GET['episode_id']) : 0;

// Fetch specific episode
$result = mysqli_query($conn, "SELECT * FROM episodes WHERE episode_id = $episode_id");

if (!$result || mysqli_num_rows($result) == 0) {
    echo "<script>alert('Episode not found.');</script>";
    echo "<script>window.location.href = 'episodes.php';</script>";
    exit();
}

$episode = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $url = trim($_POST['episode_url']);
    if (!empty($url)) {
        $sql = "UPDATE episodes SET episode_url='$url' WHERE episode_id=$episode_id";
        if (!mysqli_query($conn, $sql)) {
            echo "<script>alert('Error updating episode ID $episode_id: " . mysqli_error($conn) . "');</script>";
        } else {
            echo "<script>alert('Episode updated successfully!');</script>";
            echo "<script>window.location.href = 'episodes.php';</script>";
        }
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
        *{
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
            width: 500px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .form-container select,
        .form-container input {
            width: calc(100% - 20px);
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
        <h2>Update Episode <?php echo $episode_id; ?></h2>
        <form action="update_episode.php?episode_id=<?php echo $episode_id; ?>" method="post">
            <input type="text" name="episode_url" placeholder="Enter episode URL" value="<?php echo htmlspecialchars($episode['episode_url']); ?>" required>
            <button type="submit" name="update">Update Episode</button>
        </form>
    </div>
</body>
</html>
