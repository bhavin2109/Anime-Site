<?php
require_once 'check_admin.php';
include '../includes/dbconnect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_genre'])) {
    $new_genre = trim($_POST['new_genre']);
    if (!empty($new_genre)) {
        $stmt = mysqli_prepare($conn, "SELECT * FROM genres WHERE genre_name = ?");
        mysqli_stmt_bind_param($stmt, "s", $new_genre);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) == 0) {
            $stmt2 = mysqli_prepare($conn, "INSERT INTO genres (genre_name) VALUES (?)");
            mysqli_stmt_bind_param($stmt2, "s", $new_genre);
            mysqli_stmt_execute($stmt2);
        }
        mysqli_stmt_close($stmt);
    }
    header("Location: genres.php");
    exit();
}
if (isset($_GET['delete'])) {
    $genre_id = intval($_GET['delete']);
    $stmt = mysqli_prepare($conn, "DELETE FROM genres WHERE genre_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $genre_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: genres.php");
    exit();
}
$genres_result = mysqli_query($conn, "SELECT * FROM genres ORDER BY genre_name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Genres Management</title>
    <link rel="stylesheet" href="../css/admin_styles.css">
</head>
<body>
    <div class="admin-navbar">
        <h2>Genres</h2>
        <form class="add-genre-form" method="POST" action="genres.php" autocomplete="off">
            <input type="text" name="new_genre" placeholder="Add new genre..." required>
            <button type="submit">+ Add Genre</button>
        </form>
    </div>
    <div class="admin-container">
        <table>
            <thead><tr><th>#</th><th>Genre Name</th><th>Actions</th></tr></thead>
            <tbody>
                <?php if (mysqli_num_rows($genres_result) > 0): ?>
                    <?php $i = 1;
    while ($row = mysqli_fetch_assoc($genres_result)): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td style="text-align:left;"><?php echo htmlspecialchars($row['genre_name']); ?></td>
                            <td><a class="action-btn action-btn-delete" href="genres.php?delete=<?php echo $row['genre_id']; ?>" onclick="return confirm('Delete this genre?');">Delete</a></td>
                        </tr>
                    <?php
    endwhile; ?>
                <?php
else: ?>
                    <tr><td colspan="3" style="text-align:center;color:var(--gray-light);">No genres found.</td></tr>
                <?php
endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
