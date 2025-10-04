<?php
session_start();
require_once 'dbconnect.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../pages/login.php");
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;

// Always process as GET or POST, but only care about anime_id (from player.php button)
$anime_id = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $anime_id = isset($_POST['anime_id']) ? intval($_POST['anime_id']) : 0;
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $anime_id = isset($_GET['anime_id']) ? intval($_GET['anime_id']) : 0;
}

if ($user_id && $anime_id > 0) {
    $status = 'plan_to_watch';

    // Check if already in watchlist
    $checkQuery = "SELECT status FROM watchlist WHERE user_id = ? AND anime_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $user_id, $anime_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Already in watchlist, update status if different
        $row = $result->fetch_assoc();
        if ($row['status'] !== $status) {
            $updateQuery = "UPDATE watchlist SET status = ? WHERE user_id = ? AND anime_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("sii", $status, $user_id, $anime_id);
            $updateStmt->execute();
            $updateStmt->close();
        }
    } else {
        // Not in watchlist, insert new
        $insertQuery = "INSERT INTO watchlist (user_id, anime_id, status, added_at) VALUES (?, ?, ?, NOW())";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("iis", $user_id, $anime_id, $status);
        $insertStmt->execute();
        $insertStmt->close();
    }
    $stmt->close();

    // Redirect to watchlist after processing
    header("Location: ../pages/watchlist.php");
    exit;
}

// If not logged in or no anime_id, redirect to watchlist
header("Location: ../pages/watchlist.php");
exit;
?>