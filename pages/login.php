<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
    margin: 0;
    padding: 0;
    background-image: url('../assets/background.jpg');
    background-size: cover;
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;  
}


section {
    background-color: rgba(165, 165, 165, 0.2);
    backdrop-filter: blur(5px);
    padding: 20px 40px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    text-align: center;
    width: 300px;
    height: 400px;
}

h2 {
    margin-bottom: 20px;
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

input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    position: relative;
    z-index: 1;
    transition: padding-top 0.3s ease;
    overflow: visible;
}

input:focus {
    border-color: #0051ff;
    color: #0051ff;
    overflow: visible;
}

input:focus::placeholder {
    color: #0051ff;
    transform: translateY(-25px);
    overflow: visible;
}

input::placeholder {
    transition: transform 0.3s ease;
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
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../includes/dbconnect.php'; // Include database connection

    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if the user exists in the database
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Check if the entered password matches
        if ($password === $row['password']) { // Assuming plain text password (not recommended for real projects)
            // Set session variables for logged-in user
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $row['username'];

            // Redirect to home page
            header("Location: ..//home.php");
            exit();
        } else {
            echo "<p style='color:red;'>Invalid username or password!</p>";
        }
    } else {
        echo "<p style='color:red;'>Invalid username or password!</p>";
    }
}
?>

        <h2>Login</h2>
        <form action="login.php" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
       
        <footer>
            <p>New User ? <a href="signup.php">Sign Up</a></p>
        </footer>
    </section>
</body>
</html>
