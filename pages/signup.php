<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        /* Body */
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: url('../assets/background.jpg') no-repeat center center fixed;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    overflow: hidden;
    color: white;
}

/* Sign-up Section */
section {
    background-color: rgba(165, 165, 165, 0.2);
    backdrop-filter: blur(5px);
    padding: 20px 40px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    text-align: center;
    width: 300px;
}

h2 {
    margin-bottom: 20px;
}

input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

button {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}

footer {
    margin-top: 10px;
}

footer a {
    text-decoration: none;
    color: #007bff;
}

footer a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
    <section>
        <h2>Sign Up</h2>
        <form action="" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit" name="login">Sign Up</button>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST"){

            include '../includes/dbconnect.php';
            $username = $_POST["username"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $confirm_password = $_POST["confirm_password"];

            if ($password == $confirm_password){
                $sql = "INSERT INTO `users` (`username`, `email`, `password`) VALUES ('$username', '$email', '$password')";
                $result = mysqli_query($conn ,$sql);
            }
            if ($result) {
                // Redirect to the login page on success
                header("Location: login.php");
                exit();
            }
        }
    ?>
        <footer>
            <p>Already have an account? <a href="login.php">Login</a></p>
        </footer>
    </section>
</body>
</html>
