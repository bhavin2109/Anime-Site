<?php
session_start();

// If already logged in, redirect to admin panel
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
        // Check if the email is the admin email
        if ($email === 'admin@gmail.com') {
            // Check if admin exists in the users table by email
            // Using prepared statement to prevent SQL injection
            $stmt = $conn->prepare("SELECT user_id, username, email, password FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();

                // Check if the entered password matches
                // For now, using plain text comparison (you should use password_verify() with hashed passwords)
                if ($password === $row['password']) {
                    // Set session variables for logged-in admin
                    $_SESSION['admin_loggedin'] = true;
                    $_SESSION['admin_username'] = $row['username'];
                    $_SESSION['admin_id'] = $row['user_id'];
                    $_SESSION['admin_email'] = $row['email'];

                    // Redirect to admin panel
                    header("Location: admin.php");
                    exit();
                } else {
                    $error_message = "Invalid email or password!";
                }
            } else {
                $error_message = "Admin account not found. Please run the SQL setup file first.";
            }
            $stmt->close();
        } else {
            $error_message = "Invalid email or password!";
        }
    } else {
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
            color: #fff;
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
            margin-top: 10px;
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
            outline: none;
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

        .error-message {
            color: #ff4444;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            font-size: 14px;
        }

        footer {
            margin-top: 20px;
        }

        footer a {
            text-decoration: none;
            color: #007bff;
            color: #fff;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <section>
        <h2>Admin Login</h2>
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form action="admin_login.php" method="post">
            <input type="email" name="email" placeholder="Email" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <footer>
            <p><a href="../home.php">Back to Home</a></p>
        </footer>
    </section>
</body>
</html>

