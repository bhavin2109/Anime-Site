<?php
session_start();
include '../includes/dbconnect.php';

$anime_id = '';
$anime_name = '';

if (isset($_GET['anime_id']) && isset($_GET['anime_name'])) {
    $_SESSION['anime_id'] = $_GET['anime_id'];
    $_SESSION['anime_name'] = $_GET['anime_name'];
    $anime_id = $_SESSION['anime_id'];
    $anime_name = $_SESSION['anime_name'];
} elseif (isset($_SESSION['anime_id']) && isset($_SESSION['anime_name'])) {
    $anime_id = $_SESSION['anime_id'];
    $anime_name = $_SESSION['anime_name'];
} else {
    echo "<script>alert('Anime ID or name not provided.');</script>";
    echo "<script>window.location.href = 'anime.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $anime_id = intval($_POST["anime_id"]);
    $episode_urls = explode(',', $_POST["episode_urls"]);

    // Check if anime exists in the anime table
    $stmt = $conn->prepare("SELECT anime_id FROM anime WHERE anime_id = ?");
    $stmt->bind_param("i", $anime_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the current number of episodes for the anime
        $stmt = $conn->prepare("SELECT COUNT(*) as episode_count FROM episodes WHERE anime_id = ?");
        $stmt->bind_param("i", $anime_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $episode_count = $row['episode_count'];

        foreach ($episode_urls as $episode_url) {
            $episode_url = trim($episode_url);

            // Extract file ID from Google Drive URL
            preg_match('/\/d\/(.*?)\//', $episode_url, $matches);
            $file_id = $matches[1] ?? '';

            if (empty($file_id)) {
                echo "<script>alert('Invalid Google Drive URL: $episode_url');</script>";
            } else {
                $episode_count++;
                // Insert new episode with file ID
                $stmt = $conn->prepare("INSERT INTO episodes (anime_id, episode_url) VALUES (?, ?)");
                $stmt->bind_param("is", $anime_id, $file_id);
                if ($stmt->execute()) {
                    echo "<script>alert('Episode added successfully: Episode " . $episode_count . "');</script>";
                } else {
                    echo "<script>alert('Error adding episode: " . $stmt->error . "');</script>";
                }
            }
        }
        echo "<script>window.location.href = 'add_episode.php';</script>";
    } else {
        echo "<script>alert('Anime not found.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Episode</title>
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
        <h2>Add Episode</h2>
        <form action="add_episode.php" method="post">
            <input type="hidden" name="anime_id" value="<?php echo $anime_id; ?>">
            <p>Anime ID: <?php echo $anime_id; ?></p>
            <p>Anime Name: <?php echo $anime_name; ?></p>
            <?php
            // Fetch the current number of episodes for the anime
            $stmt = $conn->prepare("SELECT COUNT(*) as episode_count FROM episodes WHERE anime_id = ?");
            $stmt->bind_param("i", $anime_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $episode_count = $row['episode_count'] + 1;
            ?>
            <p>Next Episode Number: <?php echo $episode_count; ?></p>
            <input type="text" name="episode_urls" placeholder="Episode URLs (comma separated)" required>
            <button type="submit">Add Episodes</button>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>
