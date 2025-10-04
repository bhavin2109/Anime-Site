<?php
session_start();
require_once '../includes/dbconnect.php';

// Check if user is logged in and user_id is set
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = '';
$email = '';
$profile_picture = '';
$date_of_birth = '';

// Fetch user info from database
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
        // Update session for consistency
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['profile_picture'] = $profile_picture;
        $_SESSION['date_of_birth'] = $date_of_birth;
    }
    $stmt->close();
}

// Profile picture logic
if (!empty($profile_picture)) {
    $profile_pic_url = "../assets/profile_pics/" . htmlspecialchars($profile_picture);
} else {
    $profile_pic_url = "https://ui-avatars.com/api/?name=" . urlencode($username) . "&background=222&color=fff";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #222;
            color: #fff;
        }
        .profile-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 90vh;
        }
        .profile-card {
            background: #333;
            padding: 32px 28px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.25);
            text-align: center;
            min-width: 320px;
        }
        .profile-picture {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #3498db;
            margin-bottom: 18px;
            background: #444;
        }
        .profile-card h2 {
            margin-bottom: 18px;
            color: #3498db;
        }
        .user-info p {
            margin: 10px 0;
            font-size: 1.1em;
        }
        .profile-actions {
            margin-top: 22px;
        }
        .profile-actions a, .profile-actions button {
            display: inline-block;
            margin: 0 6px;
            padding: 10px 22px;
            border-radius: 5px;
            border: none;
            background: #3498db;
            color: #fff;
            text-decoration: none;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.2s;
        }
        .profile-actions a.logout-btn {
            background: #e74c3c;
        }
        .profile-actions a.logout-btn:hover {
            background: #c0392b;
        }
        .profile-actions a:hover, .profile-actions button:hover {
            background: #217dbb;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
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
</body>
</html>
<?php if (isset($conn)) $conn->close(); ?>
