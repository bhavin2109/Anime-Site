<?php
require_once 'check_admin.php';
include '../includes/dbconnect.php';

// Handle add genre form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_genre'])) {
    $new_genre = trim($_POST['new_genre']);
    if (!empty($new_genre)) {
        // Prevent duplicate genres
        $check_query = "SELECT * FROM genres WHERE genre_name = ?";
        $stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt, "s", $new_genre);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) == 0) {
            $insert_query = "INSERT INTO genres (genre_name) VALUES (?)";
            $stmt2 = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt2, "s", $new_genre);
            mysqli_stmt_execute($stmt2);
        }
        mysqli_stmt_close($stmt);
    }
    // Redirect to avoid resubmission
    header("Location: genres.php");
    exit();
}

// Handle delete genre
if (isset($_GET['delete'])) {
    $genre_id = intval($_GET['delete']);
    // Optionally, you may want to check if this genre is used in anime table before deleting
    $delete_query = "DELETE FROM genres WHERE genre_id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $genre_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: genres.php");
    exit();
}

// Fetch all genres
$genres_query = "SELECT * FROM genres ORDER BY genre_name";
$genres_result = mysqli_query($conn, $genres_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Genres Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ececec;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #007BFF;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h2 {
            margin: 0;
            font-size: 24px;
        }
        .add-genre-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .add-genre-form input[type="text"] {
            padding: 6px 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .add-genre-form button {
            padding: 6px 16px;
            border: none;
            border-radius: 4px;
            background-color: #28a745;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        .add-genre-form button:hover {
            background-color: #218838;
        }
        .container {
            margin: 30px auto;
            background: white;
            border-radius: 8px;
            max-width: 600px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
        }
        th, td {
            padding: 12px 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #f7f7f7;
        }
        .action-btn {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
            border: 1px solid #dc3545;
            border-radius: 4px;
            padding: 4px 10px;
            background: #fff;
            transition: background 0.2s, color 0.2s;
        }
        .action-btn:hover {
            background: #dc3545;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>Genres</h2>
        <form class="add-genre-form" method="POST" action="genres.php" autocomplete="off">
            <input type="text" name="new_genre" placeholder="Add new genre..." required>
            <button type="submit">+ Add Genre</button>
        </form>
    </div>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Genre Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($genres_result) > 0): ?>
                    <?php $i = 1; while ($row = mysqli_fetch_assoc($genres_result)): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo htmlspecialchars($row['genre_name']); ?></td>
                            <td>
                                <a class="action-btn" href="genres.php?delete=<?php echo $row['genre_id']; ?>" onclick="return confirm('Are you sure you want to delete this genre?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align:center;">No genres found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
