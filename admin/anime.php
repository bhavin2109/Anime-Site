<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anime and Movie List</title>
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
            height: auto;
            flex-direction: column;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
            max-width: 200px; /* Adjust the max-width as needed */
            word-wrap: break-word; /* Enable text wrapping */
        }
        th {
            background-color: #f2f2f2;
        }
        td {
            text-align: center; /* Center the content of the td */
        }
        .action-buttons {
            display: flex; /* Use flexbox to center the buttons */
            justify-content: center; /* Center the buttons horizontally */
            gap: 10px; /* Add some space between the buttons */
            align-self: center;
            justify-self: center;
        }
        .action-buttons a {
            text-decoration: none;
            padding: 5px 10px;
            border: 1px solid #000;
            border-radius: 5px;
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Anime and Movie List</h2>
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
            include '../pages/dbconnect.php';
            $query = "
                SELECT anime_id, anime_name, anime_type, anime_image, episodes, genre FROM anime
            ";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['anime_id']; ?></td>
                    <td><?php echo $row['anime_name']; ?></td>
                    <td><?php echo ucfirst($row['anime_type']); ?></td>
                    <td><img src="../assets/thumbnails/<?php echo $row['anime_image']; ?>" alt="<?php echo $row['anime_name']; ?>" width="100"></td>
                    <td><?php echo $row['episodes']; ?></td>
                    <td><?php echo $row['genre']; ?></td>
                    <td class="action-buttons">
                        <a href="update_<?php echo strtolower($row['anime_type']); ?>.php?id=<?php echo $row['anime_id']; ?>">Update</a>
                        <a href="anime.php?delete=<?php echo $row['anime_id']; ?>&type=<?php echo strtolower($row['anime_type']); ?>" onclick="return confirm('Are you sure you want to delete this <?php echo strtolower($row['anime_type']); ?>?');">Remove</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php
// Handle deletion
if (isset($_GET['delete']) && isset($_GET['type'])) {
    $id = $_GET['delete'];
    $type = $_GET['type'];
    $delete_query = "DELETE FROM anime WHERE anime_id = $id AND anime_type = '$type'";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('{$type} removed successfully!');</script>";
        echo "<script>window.location.href = 'anime.php';</script>";
    } else {
        echo "<script>alert('Error removing {$type}: " . mysqli_error($conn) . "');</script>";
    }
    mysqli_close($conn);
}
?>
