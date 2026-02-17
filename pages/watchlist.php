<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}
include '../includes/dbconnect.php';
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: login.php");
    exit();
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $anime_id = $_POST['anime_id'];
    $new_status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE watchlist SET status = ? WHERE user_id = ? AND anime_id = ?");
    $stmt->bind_param("sii", $new_status, $user_id, $anime_id);
    $stmt->execute();
    $stmt->close();
}

// Handle remove
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove'])) {
    $anime_id = $_POST['anime_id'];
    $stmt = $conn->prepare("DELETE FROM watchlist WHERE user_id = ? AND anime_id = ?");
    $stmt->bind_param("ii", $user_id, $anime_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch watchlist
$stmt = $conn->prepare("SELECT w.anime_id, w.status, a.anime_name, a.anime_image, a.anime_type
    FROM watchlist w JOIN anime a ON w.anime_id = a.anime_id WHERE w.user_id = ? ORDER BY w.anime_id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$watchlist = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/logo.ico">
    <title>Watchlist — Anime Streaming</title>
    <link rel="stylesheet" href="../css/shared_styles.css">
    <link rel="stylesheet" href="../css/pages/watchlist.css">
</head>
<body>
    <?php $current_page = 'watchlist.php';
include '../includes/header_shared.php'; ?>
    <main>
        <div class="watchlist-container reveal">
            <h2 class="section-title">My Watchlist</h2>
            <?php if (!empty($watchlist)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Anime</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($watchlist as $item): ?>
                    <tr>
                        <td><img src="../assets/thumbnails/<?php echo htmlspecialchars($item['anime_image']); ?>" class="thumbnail" alt=""></td>
                        <td><a href="../includes/player.php?anime_id=<?php echo htmlspecialchars($item['anime_id']); ?>&episode=1" style="color:#ef4444;text-decoration:none;font-weight:600;"><?php echo htmlspecialchars($item['anime_name']); ?></a></td>
                        <td><?php echo htmlspecialchars($item['anime_type']); ?></td>
                        <td><?php echo htmlspecialchars($item['status']); ?></td>
                        <td>
                            <div class="watchlist-actions">
                                <form method="post" class="inline-form">
                                    <input type="hidden" name="anime_id" value="<?php echo $item['anime_id']; ?>">
                                    <select name="status">
                                        <option value="Watching" <?php echo $item['status'] == 'Watching' ? 'selected' : ''; ?>>Watching</option>
                                        <option value="Completed" <?php echo $item['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                        <option value="Plan to Watch" <?php echo $item['status'] == 'Plan to Watch' ? 'selected' : ''; ?>>Plan to Watch</option>
                                        <option value="Dropped" <?php echo $item['status'] == 'Dropped' ? 'selected' : ''; ?>>Dropped</option>
                                    </select>
                                    <button type="submit" name="update_status" class="update-btn">Update</button>
                                </form>
                                <form method="post" class="inline-form">
                                    <input type="hidden" name="anime_id" value="<?php echo $item['anime_id']; ?>">
                                    <button type="submit" name="remove" class="remove-btn">Remove</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php
    endforeach; ?>
                </tbody>
            </table>
            <?php
else: ?>
            <p style="text-align:center;color:#9ca3af;padding:40px 0;font-size:1.1rem;">Your watchlist is empty. Start exploring anime!</p>
            <?php
endif; ?>
        </div>
    </main>
    <?php include '../includes/footer_shared.php'; ?>
</body>
</html>
<?php $conn->close(); ?>
