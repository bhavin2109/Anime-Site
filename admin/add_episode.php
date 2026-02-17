<?php
require_once 'check_admin.php';
include '../includes/dbconnect.php';

$anime_id = '';
$anime_name = '';
if (isset($_GET['anime_id']) && isset($_GET['anime_name'])) {
    $_SESSION['anime_id'] = $_GET['anime_id'];
    $_SESSION['anime_name'] = $_GET['anime_name'];
    $anime_id = $_SESSION['anime_id'];
    $anime_name = $_SESSION['anime_name'];
}
elseif (isset($_SESSION['anime_id']) && isset($_SESSION['anime_name'])) {
    $anime_id = $_SESSION['anime_id'];
    $anime_name = $_SESSION['anime_name'];
}
else {
    echo "<script>alert('Anime ID not provided.'); window.location.href = 'anime.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $anime_id = intval($_POST["anime_id"]);
    $episode_urls = explode(',', $_POST["episode_urls"]);
    $stmt = $conn->prepare("SELECT anime_id FROM anime WHERE anime_id = ?");
    $stmt->bind_param("i", $anime_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $added_count = 0;
        foreach ($episode_urls as $url) {
            $url = trim($url);
            preg_match('/\/d\/(.*?)\//', $url, $matches);
            $file_id = $matches[1] ?? '';
            if (!empty($file_id)) {
                $stmt2 = $conn->prepare("INSERT INTO episodes (anime_id, episode_url) VALUES (?, ?)");
                $stmt2->bind_param("is", $anime_id, $file_id);
                if ($stmt2->execute())
                    $added_count++;
            }
        }
        if ($added_count > 0)
            echo "<script>alert('Added $added_count episode(s).'); window.location.href = 'add_episode.php';</script>";
    }
}
$stmt = $conn->prepare("SELECT COUNT(*) as c FROM episodes WHERE anime_id = ?");
$stmt->bind_param("i", $anime_id);
$stmt->execute();
$next_ep = $stmt->get_result()->fetch_assoc()['c'] + 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Episode</title>
    <link rel="stylesheet" href="../css/admin_styles.css">
    <style>
        body { display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
    </style>
</head>
<body>
    <div class="form-container" style="max-width:450px;">
        <h2>Add Episode</h2>
        <form action="add_episode.php" method="post">
            <input type="hidden" name="anime_id" value="<?php echo $anime_id; ?>">
            <p>Anime: <strong style="color:var(--gold);"><?php echo htmlspecialchars($anime_name); ?></strong></p>
            <p>Next Episode: <strong style="color:var(--gold);">#<?php echo $next_ep; ?></strong></p>
            <input type="text" name="episode_urls" placeholder="Google Drive URLs (comma separated)" required>
            <button type="submit">Add Episodes</button>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>
