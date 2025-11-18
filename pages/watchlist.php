<?php
session_start();
require_once '../includes/dbconnect.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php");
    exit;
}

// Get user_id from session (fixed: was incorrectly using $_SESSION['loggedin'] which is a boolean)
$user_id = null;
if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
    $user_id = (int)$_SESSION['user_id'];
} elseif (isset($_SESSION['id']) && is_numeric($_SESSION['id'])) {
    $user_id = (int)$_SESSION['id'];
}

// If still no user_id, redirect to login
if (!$user_id) {
    header("Location: login.php");
    exit;
}

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
    <link rel="stylesheet" href="../css/shared_styles.css">
    <style>
        .watchlist-container {
            width: 100%;
            max-width: 100%;
            margin: 40px 0;
            background: rgba(26, 26, 26, 0.8);
            border: 1px solid rgba(220, 38, 38, 0.2);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(220, 38, 38, 0.3);
            padding: 30px;
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #ffffff;
            text-shadow: 0 2px 10px rgba(220, 38, 38, 0.3);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(26, 26, 26, 0.6);
            border: 1px solid rgba(220, 38, 38, 0.2);
        }
        th, td {
            padding: 12px 10px;
            text-align: center;
            color: #e5e7eb;
        }
        th {
            background: linear-gradient(135deg, #dc2626, #1e3a5f);
            color: #fff;
            border-bottom: 2px solid rgba(220, 38, 38, 0.5);
        }
        tr:nth-child(even) {
            background: rgba(30, 58, 95, 0.2);
        }
        tr:hover {
            background: rgba(220, 38, 38, 0.1);
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
            background: #dc2626;
            color: #fff;
            border: none;
            cursor: pointer;
            margin-left: 4px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
        }
        button:hover {
            background: #991b1b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.5);
        }
        button.update-btn {
            background: #1e3a5f;
        }
        button.update-btn:hover {
            background: #1e40af;
        }
        button.remove-btn {
            background: #dc2626;
        }
        .watchlist-actions {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }
        .watchlist-actions form {
            margin: 0;
        }
        main {
            width: 100%;
            padding: 0 20px;
        }
        @media (max-width: 700px) {
            .watchlist-container { 
                padding: 15px;
                margin: 20px 0;
            }
            main {
                padding: 0 10px;
            }
            table, th, td { font-size: 0.95em; }
            img.thumbnail { width: 40px; height: 55px; }
            .watchlist-actions { flex-direction: column; gap: 4px; }
        }
    </style>
</head>
<body>
    <?php 
    $current_page = 'watchlist.php';
    include '../includes/header_shared.php'; 
    ?>
    <main>
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
                    <th colspan="2">Actions</th>
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
                    <td colspan="2">
                        <div class="watchlist-actions">
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
                            <form method="post" class="inline-form" action="" onsubmit="return confirm('Remove this anime from your watchlist?');">
                                <input type="hidden" name="anime_id" value="<?php echo $row['anime_id']; ?>">
                                <button type="submit" name="remove" class="remove-btn">Remove</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p style="text-align:center;color:#e5e7eb;">Your watchlist is empty. <a href="explore.php" style="color:#dc2626;">Explore anime</a> to add!</p>
        <?php endif; ?>
    </div>
    </main>
    <?php include '../includes/footer_shared.php'; ?>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
