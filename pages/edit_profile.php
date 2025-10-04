<?php
session_start();
require_once '../includes/dbconnect.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Get user_id from session (only allow editing own profile)
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

// Fetch current user info for default form values
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

// Handle form submission (update user info)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch submitted values, fallback to current if not set
    $new_username = trim($_POST['username'] ?? $username);
    $new_email = trim($_POST['email'] ?? $email);
    $new_dob = $_POST['date_of_birth'] ?? $date_of_birth;
    $new_profile_picture = $profile_picture;

    // Handle profile picture upload if provided (no validation, keep original name)
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

    // Update user info in database
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, profile_picture = ?, date_of_birth = ? WHERE user_id = ?");
    $stmt->bind_param("ssssi", $new_username, $new_email, $new_profile_picture, $new_dob, $user_id);
    if ($stmt->execute()) {
        $success = "Profile updated successfully.";
        // Update default values for form after update
        $username = $new_username;
        $email = $new_email;
        $profile_picture = $new_profile_picture;
        $date_of_birth = $new_dob;
        // Optionally update session values if used elsewhere
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
    } else {
        $error = "Failed to update profile. Please try again.";
    }
    $stmt->close();
}

// For profile picture display
$profile_pic_url = $profile_picture ? "../assets/profile_pics/" . htmlspecialchars($profile_picture) : "https://ui-avatars.com/api/?name=" . urlencode($username) . "&background=222&color=fff";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #000000, #1a1a1a, #333333, #000000);
        }
        .edit-profile-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 60px;
        }
        .edit-profile-card {
            width: 100%;
            max-width: 420px;
            padding: 36px 32px 28px 32px;
            background-color: #222;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #fff;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.45);
            text-align: center;
            position: relative;
        }
        .edit-profile-card h2 {
            margin-bottom: 20px;
            font-size: 2em;
            color: #3498db;
            letter-spacing: 1px;
        }
        .profile-picture {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #3498db;
            margin-bottom: 22px;
            background: #444;
            box-shadow: 0 2px 8px rgba(0,0,0,0.18);
        }
        form {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        input[type="text"], input[type="email"], input[type="date"] {
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #444;
            background: #333;
            color: #fff;
            font-size: 1em;
        }
        input[type="file"] {
            color: #fff;
        }
        .form-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .save-btn {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 12px 0;
            border-radius: 6px;
            font-size: 1.08em;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
            width: 100%;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(52,152,219,0.08);
        }
        .save-btn:hover {
            background-color: #217dbb;
        }
        .cancel-btn {
            background-color: #888;
            color: #fff;
            border: none;
            padding: 12px 0;
            border-radius: 6px;
            font-size: 1.08em;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            letter-spacing: 0.5px;
            text-decoration: none;
            display: inline-block;
        }
        .cancel-btn:hover {
            background-color: #555;
        }
        .success-message {
            margin-bottom: 12px;
            padding: 10px 0;
            border-radius: 6px;
            width: 100%;
            font-size: 1em;
            font-weight: 500;
            letter-spacing: 0.2px;
            background: #2ecc40; color: #fff;
        }
        .error-message {
            margin-bottom: 12px;
            padding: 10px 0;
            border-radius: 6px;
            width: 100%;
            font-size: 1em;
            font-weight: 500;
            letter-spacing: 0.2px;
            background: #e74c3c; color: #fff;
        }
        @media (max-width: 500px) {
            .edit-profile-card {
                width: 98vw;
                padding: 18px 4vw 18px 4vw;
            }
            .profile-picture {
                width: 90px;
                height: 90px;
            }
        }
    </style>
</head>
<body>
<?php include '../includes/header.php'; ?>
<div class="edit-profile-container">
    <div class="edit-profile-card">
        <h2>Edit Profile</h2>
        <?php if ($success): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <img src="<?php echo $profile_pic_url; ?>" alt="Profile Picture" class="profile-picture">
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" placeholder="Username" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Email" required>
            <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($date_of_birth); ?>" placeholder="Date of Birth">
            <label style="text-align:left;">Profile Picture:
                <input type="file" name="profile_picture" accept="image/*">
            </label>
            <div class="form-actions">
                <button type="submit" class="save-btn">Save Changes</button>
                <a href="profile.php" class="cancel-btn">Cancel</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
