<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "anime_site";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM anime_list WHERE id=$id");
    header("Location: anime_list.php");
}

// Fetch all anime and movies
$result = $conn->query("SELECT * FROM anime_list");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anime and Movie List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
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
    <h1>Anime and Movie List</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Genre</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['genre']; ?></td>
                    <td class="action-buttons">
                        <a href="update_anime.php?id=<?php echo $row['id']; ?>">Update</a>
                        <a href="anime_list.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this anime?');">Delete</a>
                        <a href="add_anime.php?id=<?php echo $row['id']; ?>">Add</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
