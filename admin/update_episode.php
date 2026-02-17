<?php
require_once 'check_admin.php';
include_once("../includes/dbconnect.php");
$episode_id = isset($_GET['episode_id']) ? intval($_GET['episode_id']) : 0;
$result = mysqli_query($conn, "SELECT * FROM episodes WHERE episode_id = $episode_id");
if (!$result || mysqli_num_rows($result) == 0) {
    echo "<script>alert('Episode not found.'); window.location.href = 'episodes.php';</script>";
    exit();
}
$episode = mysqli_fetch_assoc($result);
if (isset($_POST['update'])) {
    $url = trim($_POST['episode_url']);
    if (!empty($url)) {
        $sql = "UPDATE episodes SET episode_url='$url' WHERE episode_id=$episode_id";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Updated!'); window.location.href = 'episodes.php';</script>";
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
    <link rel="stylesheet" href="../css/admin_styles.css">
    <style>
        body { display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
    </style>
</head>
<body>
    <div class="form-container" style="max-width:500px;">
        <h2>Update Episode #<?php echo $episode_id; ?></h2>
        <form action="update_episode.php?episode_id=<?php echo $episode_id; ?>" method="post">
            <input type="text" name="episode_url" placeholder="Episode URL" value="<?php echo htmlspecialchars($episode['episode_url']); ?>" required>
            <button type="submit" name="update">Update Episode</button>
        </form>
    </div>
</body>
</html>
