<?php
session_start();
require_once '../includes/dbconnect.php';
include '../includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['loggedin'];

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_status'], $_POST['anime_id'], $_POST['new_status'])) {
        $anime_id = intval($_POST['anime_id']);
        $new_status = $_POST['new_status'];
        $allowed_statuses = ['watching', 'completed', 'dropped', 'plan to watch'];
        if (in_array($new_status, $allowed_statuses)) {
            $stmt = $conn->prepare("UPDATE watchlist SET status = ? WHERE user_id = ? AND anime_id = ?");
            $stmt->bind_param("sii", $new_status, $user_id, $anime_id);
            $stmt->execute();
            $stmt->close();
        }
    }
    // Handle remove from watchlist
    if (isset($_POST['remove'], $_POST['anime_id'])) {
        $anime_id = intval($_POST['anime_id']);
        $stmt = $conn->prepare("DELETE FROM watchlist WHERE user_id = ? AND anime_id = ?");
        $stmt->bind_param("ii", $user_id, $anime_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch user's watchlist with anime info
$query = "SELECT w.anime_id, w.status, w.added_at, a.anime_name, a.anime_image 
          FROM watchlist w 
          JOIN anime a ON w.anime_id = a.anime_id 
          WHERE w.user_id = ?
          ORDER BY w.added_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Watchlist</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f3f3;
            margin: 0;
            padding: 0;
        }
        .watchlist-container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 30px;
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fafafa;
        }
        th, td {
            padding: 12px 10px;
            text-align: center;
        }
        th {
            background: #3498db;
            color: #fff;
        }
        tr:nth-child(even) {
            background: #f0f6fa;
        }
        img.thumbnail {
            width: 60px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }
        form.inline-form {
            display: inline;
        }
        select, button {
            padding: 5px 8px;
            border-radius: 4px;
            border: 1px solid #bbb;
            font-size: 1em;
        }
        button {
            background: #e74c3c;
            color: #fff;
            border: none;
            cursor: pointer;
            margin-left: 4px;
        }
        button.update-btn {
            background: #27ae60;
        }
        button.remove-btn {
            background: #e74c3c;
        }
        @media (max-width: 700px) {
            .watchlist-container { padding: 10px; }
            table, th, td { font-size: 0.95em; }
            img.thumbnail { width: 40px; height: 55px; }
        }
    </style>
</head>
<body>
    <div class="watchlist-container">
        <h2>Your Watchlist</h2>
        <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Anime</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th>Added At</th>
                    <th>Update Status</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <a href="../includes/player.php?anime_id=<?php echo $row['anime_id']; ?>" style="color:#2980b9;text-decoration:none;">
                            <?php echo htmlspecialchars($row['anime_name']); ?>
                        </a>
                    </td>
                    <td>
                        <?php if (!empty($row['anime_image'])): ?>
                            <img src="../assets/thumbnails/<?php echo htmlspecialchars($row['anime_image']); ?>" alt="Anime Image" class="thumbnail">
                        <?php else: ?>
                            <span>No Image</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars(ucwords($row['status'])); ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars(date('Y-m-d', strtotime($row['added_at']))); ?>
                    </td>
                    <td>
                        <form method="post" class="inline-form" action="">
                            <input type="hidden" name="anime_id" value="<?php echo $row['anime_id']; ?>">
                            <select name="new_status">
                                <?php
                                $statuses = ['watching', 'completed', 'dropped', 'plan to watch'];
                                foreach ($statuses as $status) {
                                    $selected = ($row['status'] === $status) ? 'selected' : '';
                                    echo "<option value=\"$status\" $selected>" . ucwords($status) . "</option>";
                                }
                                ?>
                            </select>
                            <button type="submit" name="update_status" class="update-btn">Update</button>
                        </form>
                    </td>
                    <td>
                        <form method="post" class="inline-form" action="" onsubmit="return confirm('Remove this anime from your watchlist?');">
                            <input type="hidden" name="anime_id" value="<?php echo $row['anime_id']; ?>">
                            <button type="submit" name="remove" class="remove-btn">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p style="text-align:center;color:#888;">Your watchlist is empty. <a href="explore.php" style="color:#3498db;">Explore anime</a> to add!</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
