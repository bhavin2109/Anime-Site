<?php
require_once 'check_admin.php';
include '../includes/dbconnect.php';
$animeCountResult = mysqli_query($conn, "SELECT COUNT(*) as count FROM anime");
$animeCount = mysqli_fetch_assoc($animeCountResult)['count'];
$moviesCountResult = mysqli_query($conn, "SELECT COUNT(*) as count FROM anime WHERE anime_type = 'Movie'");
$moviesCount = mysqli_fetch_assoc($moviesCountResult)['count'];
$usersCountResult = mysqli_query($conn, "SELECT COUNT(*) as count FROM users");
$usersCount = mysqli_fetch_assoc($usersCountResult)['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admin_styles.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Dashboard Statistics</h2>
        <div class="stats-box">
            <div class="stats-item">
                <h3>Total Anime</h3>
                <p><?php echo htmlspecialchars($animeCount); ?></p>
            </div>
            <div class="stats-item">
                <h3>Movies</h3>
                <p><?php echo htmlspecialchars($moviesCount); ?></p>
            </div>
            <div class="stats-item">
                <h3>Users</h3>
                <p><?php echo htmlspecialchars($usersCount); ?></p>
            </div>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
