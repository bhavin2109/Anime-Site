<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* General styles */
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            box-sizing: border-box;
        }

        /* Container to hold both sections */
        .container {
            display: flex;
            height: 100vh; /* Full viewport height */
            width: 100%;
            background-color: rgb(211, 171, 171);
        }

        /* Left Sidebar */
        .left {
            width: 18vw; /* Fixed width for the sidebar */
            background-color:rgb(255, 255, 255);
            color: rgb(0, 0, 0);
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100%;
        }

        .left h1{
            font-size: 22px;
        }

        .left ul{
            display: flex;
            flex-direction: column;
            padding: 2px;
        }

        .left ul li{
            background-color: rgba(111, 110, 110, 0.63);
            margin-top: 8px;
            padding: 10px 60px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .left ul li a{
            text-decoration: none;
            text-decoration-line: none;
            color: white;
        }

        .left ul li:hover {
            background-color: #34495e;
        }

        /* Right Content */
        .right {
            flex: 1; /* Occupies the remaining space */
            background-color:rgb(189, 194, 196);
            height: 100%;
        }

        .right-content h2 {
            margin-top: 1;
            text-align: center;
        }

        .right-content p {
            color: #333;
        }

        /* Page sections */
        .page {
            display: none;
        }

        .page:target {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Left Sidebar -->
        <section class="left">
                <h1>Admin Panel</h1>
                <ul type="none">
                    <li><a href="dashboard.php" target="content-frame">Dashboard</a></li>
                    <li><a href="anime.php" target="content-frame">Anime</a></li>
                    <li><a href="users.php" target="content-frame">Users</a></li>
                    <li><a href="genres.php" target="content-frame">Genres</a></li>
                    <li><a href="../home.php">Home Page</a></li>
                </ul>
        </section>
        <!-- Right Content -->
        <iframe id="content-frame" name="content-frame" src="dashboard.php" style="width: 100%; height: 100%; border: none;"></iframe>
    </div>
</body>
</html>
