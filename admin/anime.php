<?php
require_once 'check_admin.php';
?>
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
            vertical-align: middle; /* Vertically center the content of the td */
        }
        td img {
            max-width: 100px; /* Adjust the max-width as needed */
            height: 150px;
        }
        .action-buttons {
            display: flex; /* Use flexbox to center the buttons */
            flex-direction: column;
            border: none;
            justify-content: center; /* Center the buttons horizontally */
            align-self: center;
            justify-self: center;
            margin-top: 3vh;
            gap: 10px; /* Add some space between the buttons */ 
        }
        .action-buttons a {
            text-decoration: none;
            padding: 5px 10px;
            max-width: 200px;
            border: 1px solid #000;
            border-radius: 5px;
            background-color: #f2f2f2;
        }
        a {
            text-decoration: none;
            color: black;
            padding: 5px;
        }
        td.episodes {
            text-align: center; /* Center the content of the td */
            vertical-align: middle; /* Vertically center the content of the td */
            cursor: pointer; /* Change the cursor to a pointer on hover */
            transition: 0.5s; /* Add a smooth transition effect to the background color */
        }
        td.episodes:hover {
            background-color:rgb(168, 167, 167); /* Change the background color on hover */
        }
        td.episodes a {
            display: block; /* Make the link cover the entire cell */
            width: 100%;
            height: 100%;
        }
        td.episodes a:hover {
            color: #007BFF; /* Change the text color on hover */
            text-decoration: none; /* Underline the text on hover */
        }

       

    </style>
</head>
<body>
<div class="navbar" style="background-color: #007BFF; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; width: 100%;">
    <h2 style="margin: 0; font-size: 24px;">Anime and Movie List</h2>
    <a href="add.php" style="padding: 6px 16px; border: none; border-radius: 4px; background-color: #28a745; color: white; font-weight: bold; cursor: pointer; text-decoration: none;">+ Add Anime</a>
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
            $query = "
                SELECT anime_id, anime_name, anime_type, anime_image, genre FROM anime ORDER BY anime_name";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['anime_id']; ?></td>
                    <td><?php echo $row['anime_name']; ?></td>
                    <td><?php echo ucfirst($row['anime_type']); ?></td>
                    <td><img src="../assets/thumbnails/<?php echo $row['anime_image']; ?>" alt="<?php echo $row['anime_name']; ?>" width="100"></td>
                    <td class="episodes">
                        <?php
                        $anime_id = $row['anime_id'];
                        $episode_query = "SELECT COUNT(*) as episode_count FROM episodes WHERE anime_id = $anime_id";
                        $episode_result = mysqli_query($conn, $episode_query);
                        $episode_row = mysqli_fetch_assoc($episode_result);
                        $episode_page_url = "episodes.php?anime_id=$anime_id";
                        echo "<a href='$episode_page_url'>" . $episode_row['episode_count'] . "</a>";
                        ?>
                    </td>
                    <td><?php echo $row['genre']; ?></td>
                    <td class="action-buttons">
                        <a href="update_anime.php?anime_id=<?php echo $row['anime_id']; ?>">Update</a>
                        <a href="anime.php?delete=<?php echo $row['anime_id']; ?>&type=<?php echo strtolower($row['anime_type']); ?>" onclick="return confirm('Are you sure you want to delete this <?php echo strtolower($row['anime_type']); ?>?');">Remove</a>
                        <a href="add_episode.php?anime_id=<?php echo $row['anime_id']; ?>&anime_name=<?php echo urlencode($row['anime_name']); ?>">Add Episode</a>
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
