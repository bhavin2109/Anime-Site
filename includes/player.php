<?php
// Start session at the very top, before any output
session_start();

// --- Add to Watchlist Logic (moved to BEFORE any output) ---
$watchlist_message = '';
$redirect_to_login = false;
$anime_id_for_watchlist = 0;

// Get user_id from session (fixed: was incorrectly using $_SESSION['loggedin'] which is a boolean)
$user_id = null;
if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
    $user_id = (int)$_SESSION['user_id'];
} elseif (isset($_SESSION['id']) && is_numeric($_SESSION['id'])) {
    $user_id = (int)$_SESSION['id'];
}

// Only process the add-to-watchlist POST before any output
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_watchlist'])) {
    require_once 'dbconnect.php';
    $anime_id_for_watchlist = isset($_POST['anime_id']) ? intval($_POST['anime_id']) : 0;
    if (!$user_id) {
        // Not logged in, redirect to login (no output yet)
        header("Location: ../pages/login.php");
        exit;
    }
    $status = 'watching'; // Set default status as 'watching'

    // Check if already in watchlist
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
            $watchlist_message = '<span style="color:green;">Status updated in your watchlist!</span>';
        } else {
            $watchlist_message = '<span style="color:orange;">Already in your watchlist!</span>';
        }
    } else {
        $insertQuery = "INSERT INTO watchlist (user_id, anime_id, status, added_at) VALUES (?, ?, ?, NOW())";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("iis", $user_id, $anime_id_for_watchlist, $status);
        $insertStmt->execute();
        $insertStmt->close();
        $watchlist_message = '<span style="color:green;">Added to your watchlist!</span>';
    }
    $stmt->close();
    // No header() redirect here, just show the message
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player</title>
    <style>
        /* Your existing CSS code */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            font-family: Arial, sans-serif;
            overflow: hidden;
            background: linear-gradient(135deg, #000000, #1a1a1a, #333333, #000000);
            background-size: 300% 300%;
            animation: gradient-animation 10s ease infinite;
        }

        @keyframes gradient-animation {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .player-container {
            display: flex;
            height: 100%;
            justify-content: space-between;
            padding: 1px;
            gap: 5px;
            overflow: hidden;
        }

        .sidebar {
            width: 300px;
            background: transparent;
            border-radius: 10px;
            padding: 15px;
            padding-top: 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            height: auto;
            overflow-y: auto;
            scrollbar-width: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }

        .sidebar h3 {
            color: white;
            margin-top: 2vh;
            font-size: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
        }

        .episode-list {
            margin-top: 2vh;
            list-style: none;
            padding: 5px 10px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 5px;
            margin-bottom: 6vh;
            flex-wrap: wrap;
        }

        .episode-list li {
            background-color: rgba(48, 47, 47, 0.6);
            padding: 12px;
            border-radius: 8px;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .episode-list li a {
            text-decoration: none;
            color: black;
            font-size: 18px;
            font-weight: 200;
        }

        .episode-list li:hover {
            background-color: #939292;
        }

        .main-player {
            flex-grow: 1;
            text-align: center;
            height: 100%;
            overflow: hidden;
        }

        .video-player {
            width: 100%;
            height: 65vh;
            border: none;
            border-radius: 10px;
        }

        .anime-info {
            width: 350px;
            background-color: transparent;
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
            height: 100%;
            overflow-y: auto;
        }

        .anime-info img {
            width: 150px;
            height: 200px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .anime-info h2 {
            font-size: 24px;
            margin: 15px 0;
        }

        .anime-info p {
            font-size: 16px;
            color: #555;
            margin-bottom: 15px;
        }

        .anime-info form {
            margin-top: 15px;
        }

        .anime-info button {
            padding: 15px;
            margin-right: 15px;
            background-color: #5cb85c;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }

        .anime-info button:hover {
            background-color: #4cae4c;
        }

        .anime-info button:focus {
            outline: none;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="player-container">
        <!-- Sidebar for Episodes -->
        <div class="sidebar">
            <h3>List of Episodes</h3>
            <ul class="episode-list">
                <?php
                include 'dbconnect.php';
                // Fetch all episodes for the anime
                $anime_id = isset($_GET['anime_id']) ? intval($_GET['anime_id']) : 1; // Default to anime_id 1 if not provided
                $episode_id = isset($_GET['episode_id']) ? intval($_GET['episode_id']) : 1; // Default to 1 if not provided
                $episodes_query = "SELECT episode_id FROM episodes WHERE anime_id = ? ORDER BY episode_id ASC";
                $stmt = $conn->prepare($episodes_query);
                $stmt->bind_param("i", $anime_id);
                $stmt->execute();
                $episodes_result = $stmt->get_result();

                $episode_counter = 1; // Counter for sequential episode numbers
                $firstEpisode = null; // To store the first episode
                $current_episode_number = 1; // Default to 1

                // We'll also build a mapping from episode_id to episode number for later use
                $episodeIdToNumber = [];

                while ($episode = $episodes_result->fetch_assoc()) {
                    if ($firstEpisode === null) {
                        $firstEpisode = $episode; // Set the first episode
                    }
                    $episodeIdToNumber[$episode['episode_id']] = $episode_counter;
                    if ($episode['episode_id'] == $episode_id) {
                        $current_episode_number = $episode_counter;
                    }
                    echo '<li><a href="?anime_id=' . $anime_id . '&episode_id=' . $episode['episode_id'] . '">' . $episode_counter . '</a></li>';
                    $episode_counter++;
                }

                // If no episode is provided, use the first episode
                if (!isset($_GET['episode_id']) && $firstEpisode !== null) {
                    $_GET['episode_id'] = $firstEpisode['episode_id'];
                }
                ?>
            </ul>
        </div>

        <!-- Main Player Section -->
        <div class="main-player">
            <?php
            // Database connection
            include 'dbconnect.php';

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Get episode number from the URL
            $episode_id = isset($_GET['episode_id']) ? intval($_GET['episode_id']) : 1;

            // Fetch episode details from the database
            $sql = "SELECT e.episode_id, e.anime_id, a.anime_name, a.anime_image, e.episode_url
                    FROM episodes e 
                    JOIN anime a ON e.anime_id = a.anime_id 
                    WHERE e.episode_id = ? AND e.anime_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $episode_id, $anime_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $episodeDetails = $result->fetch_assoc();

            // --- Insert into history table when an episode is played ---
            // Only if user is logged in and episode/anime is valid
            if ($user_id && $episodeDetails && isset($episodeDetails['anime_id']) && isset($episodeDetails['episode_id'])) {
                // Check if a history entry already exists for this user, anime, and episode
                $checkHistoryQuery = "SELECT id FROM history WHERE user_id = ? AND anime_id = ? AND episode_id = ?";
                $checkStmt = $conn->prepare($checkHistoryQuery);
                $checkStmt->bind_param("iii", $user_id, $episodeDetails['anime_id'], $episodeDetails['episode_id']);
                $checkStmt->execute();
                $checkResult = $checkStmt->get_result();

                if ($checkResult && $checkResult->num_rows > 0) {
                    // Update watched_at to now
                    $updateHistoryQuery = "UPDATE history SET watched_at = NOW() WHERE user_id = ? AND anime_id = ? AND episode_id = ?";
                    $updateStmt = $conn->prepare($updateHistoryQuery);
                    $updateStmt->bind_param("iii", $user_id, $episodeDetails['anime_id'], $episodeDetails['episode_id']);
                    $updateStmt->execute();
                    $updateStmt->close();
                } else {
                    // Insert new history entry
                    $insertHistoryQuery = "INSERT INTO history (user_id, anime_id, episode_id, watched_at) VALUES (?, ?, ?, NOW())";
                    $insertStmt = $conn->prepare($insertHistoryQuery);
                    $insertStmt->bind_param("iii", $user_id, $episodeDetails['anime_id'], $episodeDetails['episode_id']);
                    $insertStmt->execute();
                    $insertStmt->close();
                }
                $checkStmt->close();

                // --- Add watched anime to watchlist automatically with status 'watching' ---
                $watched_anime_id = $episodeDetails['anime_id'];
                $status = 'watching';
                // Check if the anime is already in user's watchlist
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
                } else {
                    $insertWQuery = "INSERT INTO watchlist (user_id, anime_id, status, added_at) VALUES (?, ?, ?, NOW())";
                    $insertWStmt = $conn->prepare($insertWQuery);
                    $insertWStmt->bind_param("iis", $user_id, $watched_anime_id, $status);
                    $insertWStmt->execute();
                    $insertWStmt->close();
                }
                $stmtW->close();
            }
            // --- End history logic ---

            if ($episodeDetails): ?>
                <!-- Embed video using iframe -->
                <iframe src="https://drive.google.com/file/d/<?php echo htmlspecialchars($episodeDetails['episode_url']); ?>/preview" width="100%" height="600" allow="autoplay" class="video-player" allowfullscreen></iframe>
            <?php else: ?>
                <p>No video found for this episode.</p>
            <?php endif; ?>
        </div> <!-- Main Player Section ends -->

        <!-- Anime Information Section -->
        <div class="anime-info">
            <?php if (isset($episodeDetails['anime_image'])): ?>
                <img src="../assets/thumbnails/<?php echo $episodeDetails['anime_image']; ?>" alt="anime_image" class="thumbnail">
            <?php else: ?>
                <p>No image available for this anime.</p>
            <?php endif; ?>
            <h2><?php echo isset($episodeDetails['anime_name']) ? $episodeDetails['anime_name'] : 'Unknown Anime'; ?></h2>
            <p>Current Episode: <?php echo $current_episode_number; ?></p>
            <?php
            // For the form, get the anime_id from $episodeDetails if not already set
            if (!$anime_id_for_watchlist) {
                $anime_id_for_watchlist = isset($episodeDetails['anime_id']) ? intval($episodeDetails['anime_id']) : 0;
            }
            ?>

            <form method="post" action="" style="display:inline;">
                <input type="hidden" name="anime_id" value="<?php echo htmlspecialchars($anime_id_for_watchlist); ?>">
                
            </form>
            <?php if (!empty($watchlist_message)) echo $watchlist_message; ?>

        </div> <!-- Anime Information Section ends -->
    </div> <!-- Player Container ends -->

    <!-- CONTINUE WATCHING FEATURE: Show last played episode for each anime -->
    <?php if ($user_id): ?>
        <div style="width:100%;background:#222;padding:20px 0 10px 0;margin-top:10px;">
            <h2 style="color:#fff;text-align:center;">Continue Watching</h2>
            <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:30px;">
            <?php
                // Fetch last watched episode for each anime for this user
                include 'dbconnect.php';
                $continueQuery = "
                    SELECT h.anime_id, a.anime_name, a.anime_image, a.anime_type, MAX(h.watched_at) as last_watched
                    FROM history h
                    JOIN anime a ON h.anime_id = a.anime_id
                    WHERE h.user_id = ?
                    GROUP BY h.anime_id
                    ORDER BY last_watched DESC
                    LIMIT 10
                ";
                $stmt = $conn->prepare($continueQuery);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    // For each anime, get the last played episode_id for this user
                    $lastEpQuery = "SELECT episode_id, watched_at FROM history WHERE user_id = ? AND anime_id = ? ORDER BY watched_at DESC, id DESC LIMIT 1";
                    $epStmt = $conn->prepare($lastEpQuery);
                    $epStmt->bind_param("ii", $user_id, $row['anime_id']);
                    $epStmt->execute();
                    $epResult = $epStmt->get_result();
                    $epData = $epResult->fetch_assoc();
                    $epStmt->close();

                    $last_episode_id = $epData ? $epData['episode_id'] : null;
                    $last_episode_number = null;

                    // Get the episode number for this episode_id
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

                    // Fallback if not found
                    if (!$last_episode_number) $last_episode_number = 1;

                    // Link to the last played episode
                    $link = "./includes/player.php?anime_id=" . htmlspecialchars($row['anime_id']) . "&episode_id=" . htmlspecialchars($last_episode_id);

                    echo '<div style="background:#333;border-radius:10px;padding:15px 20px;min-width:220px;max-width:250px;text-align:center;box-shadow:0 2px 8px #0003;">';
                    echo '<a href="' . $link . '" style="text-decoration:none;color:inherit;">';
                    echo '<img src="./assets/thumbnails/' . htmlspecialchars($row['anime_image']) . '" alt="thumb" style="width:120px;height:160px;border-radius:6px;box-shadow:0 2px 8px #0006;"><br>';
                    echo '<div style="margin-top:10px;font-size:1.1em;font-weight:bold;color:#fff;">' . htmlspecialchars($row['anime_name']) . '</div>';
                    echo '<div style="color:#bbb;font-size:0.95em;">Type: ' . htmlspecialchars($row['anime_type']) . '</div>';
                    echo '<div style="color:#6cf;font-size:1em;margin-top:6px;">Last Played Episode: ' . htmlspecialchars($last_episode_number) . '</div>';
                    echo '</a>';
                    echo '</div>';
                }
                $stmt->close();
            ?>
            </div>
        </div>
    <?php endif; ?>
</body>

</html>