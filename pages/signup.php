<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/logo.ico">
    <title>Sign Up — Anime Streaming</title>
    <link rel="stylesheet" href="../css/shared_styles.css">
    <link rel="stylesheet" href="../css/pages/auth.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <img src="../assets/logo.ico" alt="Logo" class="auth-logo">
        <h2>Create Account</h2>
        <form action="" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit" name="login">Sign Up</button>
        </form>
        <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../includes/dbconnect.php';
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($password == $confirm_password) {
        $sql = "INSERT INTO `users` (`username`, `email`, `password`) VALUES ('$username', '$email', '$password')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            header("Location: login.php");
            exit();
        }
    }
    else {
        echo "<p class='auth-error'>Passwords do not match!</p>";
    }
}
?>
        <div class="auth-footer">
            <p>Already have an account? <a href="login.php">Sign In</a></p>
        </div>
    </div>
</body>
</html>
