<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
        }

        /* Profile container */
        .profile-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5);
        }

        /* Profile card */
        .profile-card {
            width: 350px;
            padding: 20px;
            background-color: #222;
            color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        .profile-card h2 {
            margin-bottom: 20px;
            font-size: 1.8em;
            color: #3498db;
        }

        .user-info {
            margin-bottom: 30px;
        }

        .user-info p {
            font-size: 1.1em;
            margin: 10px 0;
        }

        /* Logout button */
        .logout-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            font-size: 1.2em;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>

<body>
    <?php
    // Start the session
    session_start();
    require_once 'dbconnect.php'; // Include the database connection

    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: login.php"); // Redirect to login page if not logged in
        exit;
    } else {
        $username = $_SESSION['username']; // Get the session username
        
        // Fetch email from the database if not set in session
        if (!isset($_SESSION['email'])) {
            $stmt = $conn->prepare("SELECT email FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->bind_result($email);
            $stmt->fetch();
            $_SESSION['email'] = $email; // Store email in session
            $stmt->close();
        } else {
            $email = $_SESSION['email']; // Get the session email
        }
    }
    ?>
    <div class="profile-container">
        <div class="profile-card">
            <h2>User Profile</h2>
            <div class="user-info">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($username); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            </div>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
</body>

</html>