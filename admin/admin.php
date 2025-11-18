<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: admin_login.php");
    exit();
}
?>
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
            transition: all 0.3s ease;
        }

        .left ul li a{
            text-decoration: none;
            text-decoration-line: none;
            color: white;
        }

        .left ul li:hover {
            background-color: #34495e;
            transform: translateX(5px);
        }

        .left ul li.active {
            background: linear-gradient(135deg, #dc2626, #1e3a5f);
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4);
        }

        .left ul li.active:hover {
            background: linear-gradient(135deg, #991b1b, #1e40af);
            transform: translateX(5px);
        }

        .logout-btn {
            background-color: #e74c3c !important;
            margin-top: 20px;
        }

        .logout-btn:hover {
            background-color: #c0392b !important;
        }

        .admin-info {
            margin-top: 20px;
            padding: 10px;
            text-align: center;
            color: #333;
            font-size: 14px;
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
                <ul type="none" id="admin-nav">
                    <li class="nav-item active" data-page="dashboard.php"><a href="dashboard.php" target="content-frame">Dashboard</a></li>
                    <li class="nav-item" data-page="anime.php"><a href="anime.php" target="content-frame">Anime</a></li>
                    <li class="nav-item" data-page="users.php"><a href="users.php" target="content-frame">Users</a></li>
                    <li class="nav-item" data-page="genres.php"><a href="genres.php" target="content-frame">Genres</a></li>
                    <li class="nav-item" data-page="admin_logout.php"><a href="admin_logout.php">Logout</a></li>
                </ul>
                
                
        </section>
        <!-- Right Content -->
        <iframe id="content-frame" name="content-frame" src="dashboard.php" style="width: 100%; height: 100%; border: none;"></iframe>
        <script>
            // Update active nav item when links are clicked
            document.querySelectorAll('#admin-nav a').forEach(link => {
                link.addEventListener('click', function() {
                    const page = this.getAttribute('href');
                    const navItems = document.querySelectorAll('#admin-nav .nav-item');
                    navItems.forEach(item => {
                        item.classList.remove('active');
                        if (item.querySelector('a').getAttribute('href') === page) {
                            item.classList.add('active');
                        }
                    });
                });
            });
        </script>
    </div>
</body>
</html>
