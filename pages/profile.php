<?php
session_start();
require_once '../includes/dbconnect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = '';
$email = '';
$profile_picture = '';
$date_of_birth = '';

$stmt = $conn->prepare("SELECT username, email, profile_picture, date_of_birth FROM users WHERE user_id = ?");
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($db_username, $db_email, $db_profile_picture, $db_dob);
    if ($stmt->fetch()) {
        $username = $db_username;
        $email = $db_email;
        $profile_picture = $db_profile_picture;
        $date_of_birth = $db_dob;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['profile_picture'] = $profile_picture;
        $_SESSION['date_of_birth'] = $date_of_birth;
    }
    $stmt->close();
}

if (!empty($profile_picture)) {
    $profile_pic_url = "../assets/profile_pics/" . htmlspecialchars($profile_picture);
}
else {
    $profile_pic_url = "https://ui-avatars.com/api/?name=" . urlencode($username) . "&background=ccaa00&color=090c11&size=128";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/logo.ico">
    <title>Profile — Anime Streaming</title>
    <link rel="stylesheet" href="../css/shared_styles.css">
    <link rel="stylesheet" href="../css/pages/profile.css">
</head>
<body>
    <?php
$current_page = 'profile.php';
include '../includes/header_shared.php';
?>
    <div class="profile-container">
        <div class="profile-card">
            <h2>User Profile</h2>
            <img src="<?php echo $profile_pic_url; ?>" alt="Profile Picture" class="profile-picture">
            <div class="user-info">
                <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($date_of_birth); ?></p>
            </div>
            <div class="profile-actions">
                <a href="edit_profile.php">Edit Profile</a>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
    </div>
    <?php include '../includes/footer_shared.php'; ?>
</body>
</html>
<?php if (isset($conn))
    $conn->close(); ?>
