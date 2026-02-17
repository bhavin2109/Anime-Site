<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/logo.ico">
    <title>Login — Anime Streaming</title>
    <link rel="stylesheet" href="../css/shared_styles.css">
    <link rel="stylesheet" href="../css/pages/auth.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <img src="../assets/logo.ico" alt="Logo" class="auth-logo">
        <?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../includes/dbconnect.php';
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if ($password === $row['password']) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['user_id'];
            header("Location: ../home.php");
            exit();
        }
        else {
            echo "<p class='auth-error'>Invalid username or password!</p>";
        }
    }
    else {
        echo "<p class='auth-error'>Invalid username or password!</p>";
    }
}
?>
        <h2>Welcome Back</h2>
        <form action="login.php" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Sign In</button>
        </form>
        <div class="auth-footer">
            <p>New here? <a href="signup.php">Create an account</a></p>
        </div>
    </div>
</body>
</html>
