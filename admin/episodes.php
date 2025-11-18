<?php
require_once 'check_admin.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Episodes List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td:last-child {
            text-align: center;
        }
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .action-buttons a {
            text-decoration: none;
            padding: 5px 10px;
            border: 1px solid #000;
            border-radius: 5px;
            background-color: #f2f2f2;
            transition: background-color 0.3s ease;
        }

        .action-buttons a:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    
    <?php
    include '../includes/dbconnect.php';
    
    // Handle delete request
    if (isset($_GET['delete'])) {
        $episode_id = intval($_GET['delete']);
        $delete_query = "DELETE FROM episodes WHERE episode_id = $episode_id";
        mysqli_query($conn, $delete_query);
        header("Location: episodes.php?anime_id=" . intval($_GET['anime_id']));
        exit();
    }
    
    if (isset($_GET['anime_id'])) {
        $anime_id = intval($_GET['anime_id']);
        $anime_query = "SELECT anime_name FROM anime WHERE anime_id = $anime_id";
        $anime_result = mysqli_query($conn, $anime_query);
        if ($anime_row = mysqli_fetch_assoc($anime_result)) {
            echo "<h3 style=text-align:center;>" . $anime_row['anime_name'] . "</h3>";
        } else {
            echo "<h3>Anime not found</h3>";
        }
    }
    ?>
    <table>
        <thead>
            <tr>
                <th>Episode No</th>
                <th>Episode ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include '../includes/dbconnect.php';
            if (isset($_GET['anime_id'])) {
                $anime_id = intval($_GET['anime_id']);
                $query = "SELECT * FROM episodes WHERE anime_id = $anime_id";
                $result = mysqli_query($conn, $query);
                $count = 1;
                while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $count++; ?></td>
                        <td><?php echo $row['episode_id']; ?></td>  
                        <td class="action-buttons">
                            <a href="update_episode.php?episode_id=<?php echo $row['episode_id']; ?>">Update</a>
                            <a href="episodes.php?delete=<?php echo $row['episode_id']; ?>&anime_id=<?php echo $anime_id; ?>" onclick="return confirm('Are you sure you want to delete this episode?');">Remove</a>
                        </td>
                    </tr>
                <?php endwhile;
            } else {
                echo "<tr><td colspan='3'>No anime selected.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
