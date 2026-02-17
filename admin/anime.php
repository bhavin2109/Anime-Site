<?php
require_once 'check_admin.php';
// Handle deletion
if (isset($_GET['delete']) && isset($_GET['type'])) {
    include '../includes/dbconnect.php';
    $id = intval($_GET['delete']);
    $type = $_GET['type'];
    $stmt = $conn->prepare("DELETE FROM anime WHERE anime_id = ? AND anime_type = ?");
    $stmt->bind_param("is", $id, $type);
    if ($stmt->execute()) {
        echo "<script>alert('{$type} removed successfully!'); window.location.href = 'anime.php';</script>";
    }
    else {
        echo "<script>alert('Error removing.');</script>";
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anime & Movie List</title>
    <link rel="stylesheet" href="../css/admin_styles.css">
</head>
<body>
    <div class="admin-navbar">
        <h2>Anime & Movie List</h2>
        <a href="add.php" class="btn-add">+ Add Anime</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Image</th>
                <th>Episodes</th>
                <th>Genre</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
include '../includes/dbconnect.php';
$result = mysqli_query($conn, "SELECT anime_id, anime_name, anime_type, anime_image, genre FROM anime ORDER BY anime_name");
while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['anime_id']; ?></td>
                    <td style="text-align:left;"><?php echo htmlspecialchars($row['anime_name']); ?></td>
                    <td><?php echo ucfirst($row['anime_type']); ?></td>
                    <td><img src="../assets/thumbnails/<?php echo $row['anime_image']; ?>" alt="" style="max-width:80px;height:110px;object-fit:cover;"></td>
                    <td>
                        <?php
    $aid = $row['anime_id'];
    $er = mysqli_query($conn, "SELECT COUNT(*) as c FROM episodes WHERE anime_id = $aid");
    $ec = mysqli_fetch_assoc($er)['c'];
    echo "<a href='episodes.php?anime_id=$aid'>$ec</a>";
?>
                    </td>
                    <td><?php echo htmlspecialchars($row['genre']); ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="update_anime.php?anime_id=<?php echo $row['anime_id']; ?>">Update</a>
                            <a href="anime.php?delete=<?php echo $row['anime_id']; ?>&type=<?php echo strtolower($row['anime_type']); ?>" class="action-btn-delete" onclick="return confirm('Delete this?');">Remove</a>
                            <a href="add_episode.php?anime_id=<?php echo $row['anime_id']; ?>&anime_name=<?php echo urlencode($row['anime_name']); ?>">+ Episode</a>
                        </div>
                    </td>
                </tr>
            <?php
endwhile; ?>
        </tbody>
    </table>
</body>
</html>
