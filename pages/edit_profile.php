<?php
session_start();
require_once '../includes/dbconnect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'] ?? 1;
if (!$user_id) {
    header("Location: login.php");
    exit;
}

$success = '';
$error = '';
$username = '';
$email = '';
$date_of_birth = '';
$profile_picture = '';

$stmt = $conn->prepare("SELECT username, email, profile_picture, date_of_birth FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($db_username, $db_email, $db_profile_picture, $db_dob);
if ($stmt->fetch()) {
    $username = $db_username;
    $email = $db_email;
    $profile_picture = $db_profile_picture;
    $date_of_birth = $db_dob;
}
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = trim($_POST['username'] ?? $username);
    $new_email = trim($_POST['email'] ?? $email);
    $new_dob = $_POST['date_of_birth'] ?? $date_of_birth;
    $new_profile_picture = $profile_picture;

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_name = basename($_FILES['profile_picture']['name']);
        $upload_dir = "../assets/profile_pics/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $target_path = $upload_dir . $file_name;
        if (move_uploaded_file($file_tmp, $target_path)) {
            $new_profile_picture = $file_name;
        }
    }

    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, profile_picture = ?, date_of_birth = ? WHERE user_id = ?");
    $stmt->bind_param("ssssi", $new_username, $new_email, $new_profile_picture, $new_dob, $user_id);
    if ($stmt->execute()) {
        $success = "Profile updated successfully.";
        $username = $new_username;
        $email = $new_email;
        $profile_picture = $new_profile_picture;
        $date_of_birth = $new_dob;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['profile_picture'] = $profile_picture;
    }
    else {
        $error = "Failed to update profile. Please try again.";
    }
    $stmt->close();
}

$profile_pic_url = $profile_picture ? "../assets/profile_pics/" . htmlspecialchars($profile_picture) : "https://ui-avatars.com/api/?name=" . urlencode($username) . "&background=ccaa00&color=090c11&size=128";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/logo.ico">
    <title>Edit Profile — Anime Streaming</title>
    <link rel="stylesheet" href="../css/shared_styles.css">
    <link rel="stylesheet" href="../css/pages/profile.css">
</head>
<body>
<?php

$current_page = 'edit_profile.php';
include '../includes/header_shared.php';

?>
<div class="edit-profile-container">
    <div class="edit-profile-card">
        <h2>Edit Profile</h2>
        <?php if ($success): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php
endif; ?>
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php
endif; ?>
        <img src="<?php echo $profile_pic_url; ?>" alt="Profile Picture" class="profile-picture">
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" placeholder="Username" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Email" required>
            <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($date_of_birth); ?>" placeholder="Date of Birth">
            <label>Profile Picture:
                <input type="file" name="profile_picture" accept="image/*">
            </label>
            <div class="form-actions">
                <button type="submit" class="save-btn">Save Changes</button>
                <a href="profile.php" class="cancel-btn">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php include '../includes/footer_shared.php'; ?>
</body>
</html>
