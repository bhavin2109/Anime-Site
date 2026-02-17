<?php
session_start();
$watchlist_message = '';
$redirect_to_login = false;
$anime_id_for_watchlist = 0;

$user_id = null;
if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
    $user_id = (int)$_SESSION['user_id'];
}
elseif (isset($_SESSION['id']) && is_numeric($_SESSION['id'])) {
    $user_id = (int)$_SESSION['id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_watchlist'])) {
    require_once 'dbconnect.php';
    $anime_id_for_watchlist = isset($_POST['anime_id']) ? intval($_POST['anime_id']) : 0;
    if (!$user_id) {
        header("Location: ../pages/login.php");
        exit;
    }
    $status = 'watching';
    $checkQuery = "SELECT status FROM watchlist WHERE user_id = ? AND anime_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $user_id, $anime_id_for_watchlist);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['status'] !== $status) {
            $updateQuery = "UPDATE watchlist SET status = ? WHERE user_id = ? AND anime_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("sii", $status, $user_id, $anime_id_for_watchlist);
            $updateStmt->execute();
            $updateStmt->close();
            $watchlist_message = '<span style="color:#4ade80;">Status updated in your watchlist!</span>';
        }
        else {
            $watchlist_message = '<span style="color:#fbbf24;">Already in your watchlist!</span>';
        }
    }
    else {
        $insertQuery = "INSERT INTO watchlist (user_id, anime_id, status, added_at) VALUES (?, ?, ?, NOW())";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("iis", $user_id, $anime_id_for_watchlist, $status);
        $insertStmt->execute();
        $insertStmt->close();
        $watchlist_message = '<span style="color:#4ade80;">Added to your watchlist!</span>';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en" class="player-page">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/logo.ico">
    <title>Player — Anime Streaming</title>
    <link rel="stylesheet" href="../css/shared_styles.css">
    <link rel="stylesheet" href="../css/pages/player.css">
</head>
<body>
    <?php include 'header_shared.php'; ?>
    <div class="player-container">
        <div class="sidebar">
            <h3>List of Episodes</h3>
            <ul class="episode-list">
                <?php
include 'dbconnect.php';
$anime_id = isset($_GET['anime_id']) ? intval($_GET['anime_id']) : 1;
$episode_id = isset($_GET['episode_id']) ? intval($_GET['episode_id']) : 1;
$episodes_query = "SELECT episode_id FROM episodes WHERE anime_id = ? ORDER BY episode_id ASC";
$stmt = $conn->prepare($episodes_query);
$stmt->bind_param("i", $anime_id);
$stmt->execute();
$episodes_result = $stmt->get_result();
$episode_counter = 1;
$firstEpisode = null;
$current_episode_number = 1;
$episodeIdToNumber = [];
while ($episode = $episodes_result->fetch_assoc()) {
    if ($firstEpisode === null)
        $firstEpisode = $episode;
    $episodeIdToNumber[$episode['episode_id']] = $episode_counter;
    if ($episode['episode_id'] == $episode_id)
        $current_episode_number = $episode_counter;
    echo '<li><a href="?anime_id=' . $anime_id . '&episode_id=' . $episode['episode_id'] . '">' . $episode_counter . '</a></li>';
    $episode_counter++;
}
if (!isset($_GET['episode_id']) && $firstEpisode !== null)
    $_GET['episode_id'] = $firstEpisode['episode_id'];
?>
            </ul>
        </div>
        <div class="main-player">
            <?php
include 'dbconnect.php';
if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);
$episode_id = isset($_GET['episode_id']) ? intval($_GET['episode_id']) : 1;
$sql = "SELECT e.episode_id, e.anime_id, a.anime_name, a.anime_image, e.episode_url FROM episodes e JOIN anime a ON e.anime_id = a.anime_id WHERE e.episode_id = ? AND e.anime_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $episode_id, $anime_id);
$stmt->execute();
$result = $stmt->get_result();
$episodeDetails = $result->fetch_assoc();

if ($user_id && $episodeDetails && isset($episodeDetails['anime_id']) && isset($episodeDetails['episode_id'])) {
    $checkHistoryQuery = "SELECT id FROM history WHERE user_id = ? AND anime_id = ? AND episode_id = ?";
    $checkStmt = $conn->prepare($checkHistoryQuery);
    $checkStmt->bind_param("iii", $user_id, $episodeDetails['anime_id'], $episodeDetails['episode_id']);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    if ($checkResult && $checkResult->num_rows > 0) {
        $updateHistoryQuery = "UPDATE history SET watched_at = NOW() WHERE user_id = ? AND anime_id = ? AND episode_id = ?";
        $updateStmt = $conn->prepare($updateHistoryQuery);
        $updateStmt->bind_param("iii", $user_id, $episodeDetails['anime_id'], $episodeDetails['episode_id']);
        $updateStmt->execute();
        $updateStmt->close();
    }
    else {
        $insertHistoryQuery = "INSERT INTO history (user_id, anime_id, episode_id, watched_at) VALUES (?, ?, ?, NOW())";
        $insertStmt = $conn->prepare($insertHistoryQuery);
        $insertStmt->bind_param("iii", $user_id, $episodeDetails['anime_id'], $episodeDetails['episode_id']);
        $insertStmt->execute();
        $insertStmt->close();
    }
    $checkStmt->close();

    $watched_anime_id = $episodeDetails['anime_id'];
    $status = 'watching';
    $checkWQuery = "SELECT status FROM watchlist WHERE user_id = ? AND anime_id = ?";
    $stmtW = $conn->prepare($checkWQuery);
    $stmtW->bind_param("ii", $user_id, $watched_anime_id);
    $stmtW->execute();
    $resultW = $stmtW->get_result();
    if ($resultW && $resultW->num_rows > 0) {
        $rowW = $resultW->fetch_assoc();
        if ($rowW['status'] !== $status) {
            $updateWQuery = "UPDATE watchlist SET status = ? WHERE user_id = ? AND anime_id = ?";
            $updateWStmt = $conn->prepare($updateWQuery);
            $updateWStmt->bind_param("sii", $status, $user_id, $watched_anime_id);
            $updateWStmt->execute();
            $updateWStmt->close();
        }
    }
    else {
        $insertWQuery = "INSERT INTO watchlist (user_id, anime_id, status, added_at) VALUES (?, ?, ?, NOW())";
        $insertWStmt = $conn->prepare($insertWQuery);
        $insertWStmt->bind_param("iis", $user_id, $watched_anime_id, $status);
        $insertWStmt->execute();
        $insertWStmt->close();
    }
    $stmtW->close();
}
if ($episodeDetails): ?>
                <iframe src="https://drive.google.com/file/d/<?php echo htmlspecialchars($episodeDetails['episode_url']); ?>/preview" width="100%" height="600" allow="autoplay" class="video-player" allowfullscreen></iframe>
            <?php
else: ?>
                <p style="color:#9ca3af;padding:40px;">No video found for this episode.</p>
            <?php
endif; ?>
        </div>
        <div class="anime-info">
            <?php if (isset($episodeDetails['anime_image'])): ?>
                <img src="../assets/thumbnails/<?php echo $episodeDetails['anime_image']; ?>" alt="anime_image">
            <?php
else: ?>
                <p>No image available.</p>
            <?php
endif; ?>
            <h2><?php echo isset($episodeDetails['anime_name']) ? $episodeDetails['anime_name'] : 'Unknown Anime'; ?></h2>
            <p>Current Episode: <?php echo $current_episode_number; ?></p>
            <?php
if (!$anime_id_for_watchlist)
    $anime_id_for_watchlist = isset($episodeDetails['anime_id']) ? intval($episodeDetails['anime_id']) : 0;
?>
            <form method="post" action="" style="display:inline;">
                <input type="hidden" name="anime_id" value="<?php echo htmlspecialchars($anime_id_for_watchlist); ?>">
                <button type="submit" name="add_to_watchlist">+ Watchlist</button>
            </form>
            <?php if (!empty($watchlist_message))
    echo $watchlist_message; ?>
        </div>
    </div>

    <?php if ($user_id): ?>
    <div class="continue-watching-player">
        <h2>Continue Watching</h2>
        <div class="cw-grid">
        <?php
    include 'dbconnect.php';
    $continueQuery = "SELECT h.anime_id, a.anime_name, a.anime_image, a.anime_type, MAX(h.watched_at) as last_watched
                FROM history h JOIN anime a ON h.anime_id = a.anime_id WHERE h.user_id = ? GROUP BY h.anime_id ORDER BY last_watched DESC LIMIT 10";
    $stmt = $conn->prepare($continueQuery);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $lastEpQuery = "SELECT episode_id FROM history WHERE user_id = ? AND anime_id = ? ORDER BY watched_at DESC, id DESC LIMIT 1";
        $epStmt = $conn->prepare($lastEpQuery);
        $epStmt->bind_param("ii", $user_id, $row['anime_id']);
        $epStmt->execute();
        $epResult = $epStmt->get_result();
        $epData = $epResult->fetch_assoc();
        $epStmt->close();
        $last_episode_id = $epData ? $epData['episode_id'] : null;
        $last_episode_number = null;
        if ($last_episode_id) {
            $epNumQuery = "SELECT episode_id FROM episodes WHERE anime_id = ? ORDER BY episode_id ASC";
            $epNumStmt = $conn->prepare($epNumQuery);
            $epNumStmt->bind_param("i", $row['anime_id']);
            $epNumStmt->execute();
            $epNumResult = $epNumStmt->get_result();
            $epNum = 1;
            while ($epRow = $epNumResult->fetch_assoc()) {
                if ($epRow['episode_id'] == $last_episode_id) {
                    $last_episode_number = $epNum;
                    break;
                }
                $epNum++;
            }
            $epNumStmt->close();
        }
        if (!$last_episode_number)
            $last_episode_number = 1;
        $link = "player.php?anime_id=" . htmlspecialchars($row['anime_id']) . "&episode_id=" . htmlspecialchars($last_episode_id);
        echo '<div class="cw-card"><a href="' . $link . '">';
        echo '<img src="../assets/thumbnails/' . htmlspecialchars($row['anime_image']) . '" alt="thumb">';
        echo '<div class="cw-name">' . htmlspecialchars($row['anime_name']) . '</div>';
        echo '<div class="cw-type">' . htmlspecialchars($row['anime_type']) . '</div>';
        echo '<div class="cw-episode">Episode ' . htmlspecialchars($last_episode_number) . '</div>';
        echo '</a></div>';
    }
    $stmt->close();
?>
        </div>
    </div>
    <?php
endif; ?>
</body>
</html>