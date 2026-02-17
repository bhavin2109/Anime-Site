<?php
require_once 'check_admin.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Episodes List</title>
    <link rel="stylesheet" href="../css/admin_styles.css">
</head>
<body>
    <?php
include '../includes/dbconnect.php';
if (isset($_GET['delete'])) {
    $episode_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM episodes WHERE episode_id = $episode_id");
    header("Location: episodes.php?anime_id=" . intval($_GET['anime_id']));
    exit();
}
$anime_name = 'Unknown';
if (isset($_GET['anime_id'])) {
    $anime_id = intval($_GET['anime_id']);
    $anime_result = mysqli_query($conn, "SELECT anime_name FROM anime WHERE anime_id = $anime_id");
    if ($row = mysqli_fetch_assoc($anime_result))
        $anime_name = $row['anime_name'];
}
?>
    <div class="admin-navbar">
        <h2>Episodes — <?php echo htmlspecialchars($anime_name); ?></h2>
    </div>
    <div class="admin-container" style="max-width:700px;">
        <table>
            <thead><tr><th>Episode No</th><th>Episode ID</th><th>Actions</th></tr></thead>
            <tbody>
                <?php
include '../includes/dbconnect.php';
if (isset($_GET['anime_id'])) {
    $anime_id = intval($_GET['anime_id']);
    $result = mysqli_query($conn, "SELECT * FROM episodes WHERE anime_id = $anime_id");
    $count = 1;
    while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $count++; ?></td>
                            <td><?php echo $row['episode_id']; ?></td>
                            <td>
                                <div class="action-buttons" style="flex-direction:row;justify-content:center;">
                                    <a href="update_episode.php?episode_id=<?php echo $row['episode_id']; ?>">Update</a>
                                    <a href="episodes.php?delete=<?php echo $row['episode_id']; ?>&anime_id=<?php echo $anime_id; ?>" class="action-btn-delete" onclick="return confirm('Delete this episode?');">Remove</a>
                                </div>
                            </td>
                        </tr>
                    <?php
    endwhile;
}
else {
    echo "<tr><td colspan='3' style='text-align:center;color:var(--gray-light);'>No anime selected.</td></tr>";
}
?>
            </tbody>
        </table>
    </div>
</body>
</html>
