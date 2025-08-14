<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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
            background: linear-gradient(135deg, #000000, #1a1a1a, #333333, #000000);
            background-size: 300% 300%;
            animation: gradient-animation 4s ease infinite;
            margin: 0;
            overflow: hidden;
        }

         @keyframes gradient-animation {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Header styles */
        header {
            position: fixed;
            top: 0;
            width: 100%;
            background-color: rgba(159, 159, 159, 0.8);
            z-index: 1000;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 10px 20px;
        }

        .logo img {
            height: 50px;
        }

        .options a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }

        .search-section input {
            padding: 5px;
        }

        /* Profile container */
        .profile-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 90vh;
        }

        /* Profile card */
        .profile-card {
            width: 70%;
            height: 80%;
            padding: 20px;
            background-color: #222;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
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
    <!-- PHP User Profile Logic -->
    <?php
    // Start the session
    session_start();
    include '../includes/header.php';
    require_once '../includes/dbconnect.php'; // Include the database connection

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

    <!-- Profile Container -->
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
