<?php
session_start();
if (isset($_SESSION['admin_loggedin']) && $_SESSION['admin_loggedin'] === true) {
    header("Location: admin.php");
    exit();
}
$error_message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../includes/dbconnect.php';
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    if (!empty($email) && !empty($password)) {
        if ($email === 'admin@gmail.com') {
            $stmt = $conn->prepare("SELECT user_id, username, email, password FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                if ($password === $row['password']) {
                    $_SESSION['admin_loggedin'] = true;
                    $_SESSION['admin_username'] = $row['username'];
                    $_SESSION['admin_id'] = $row['user_id'];
                    $_SESSION['admin_email'] = $row['email'];
                    header("Location: admin.php");
                    exit();
                }
                else {
                    $error_message = "Invalid email or password!";
                }
            }
            else {
                $error_message = "Admin account not found.";
            }
            $stmt->close();
        }
        else {
            $error_message = "Invalid email or password!";
        }
    }
    else {
        $error_message = "Please fill in all fields!";
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/shared_styles.css">
    <link rel="stylesheet" href="../css/pages/auth.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <h2>Admin Login</h2>
        <p class="subtitle">Sign in to manage your anime platform</p>
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php
endif; ?>
        <form action="admin_login.php" method="post">
            <input type="email" name="email" placeholder="Admin Email" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <div class="auth-switch">
            <a href="../home.php">← Back to Home</a>
        </div>
    </div>
</body>
</html>
