<?php
session_start();
include '../includes/dbconnect.php';

// Fetch counts from the database
$animeCountQuery = "SELECT COUNT(*) as count FROM anime";
$animeCountResult = mysqli_query($conn, $animeCountQuery);
$animeCount = mysqli_fetch_assoc($animeCountResult)['count'];

$moviesCountQuery = "SELECT COUNT(*) as count FROM anime WHERE anime_type = 'Movie'";
$moviesCountResult = mysqli_query($conn, $moviesCountQuery);
$moviesCount = mysqli_fetch_assoc($moviesCountResult)['count'];

$usersCountQuery = "SELECT COUNT(*) as count FROM users";
$usersCountResult = mysqli_query($conn, $usersCountQuery);
$usersCount = mysqli_fetch_assoc($usersCountResult)['count'];

// Debug statements
error_log('Anime count: ' . $animeCount);
error_log('Movies count: ' . $moviesCount);
error_log('Users count: ' . $usersCount);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: rgb(150, 150, 150);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 20px;
        }

        .dashboard-container {
            width: 80%;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .stats-box {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .stats-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 200px;
            text-align: center;
        }

        .stats-item h3 {
            margin: 10px 0;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <h2>Dashboard Statistics</h2>
    <div class="stats-box">
        <div class="stats-item">
            <h3>Anime</h3>
            <p><?php echo htmlspecialchars($animeCount); ?></p>
        </div>
        <div class="stats-item">
            <h3>Users</h3>
            <p><?php echo htmlspecialchars($usersCount); ?></p>
        </div>
    </div>
</div>

</body>
</html>
<?php
$conn->close();
?>
