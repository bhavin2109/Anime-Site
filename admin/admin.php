<?php
session_start();
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
    <link rel="stylesheet" href="../css/admin_styles.css">
    <style>
        body, html { margin: 0; padding: 0; overflow: hidden; }
        .container { display: flex; height: 100vh; width: 100%; }
        .left {
            width: 240px; min-width: 240px;
            background: var(--gradient-sidebar);
            border-right: 1px solid var(--glass-border);
            display: flex; flex-direction: column; align-items: center;
            padding: 24px 0;
        }
        .left h1 {
            font-size: 1.2rem; margin-bottom: 24px;
            background: var(--gradient-gold);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .left ul { display: flex; flex-direction: column; gap: 6px; width: 90%; padding: 0; }
        .left ul li {
            background: rgba(255,255,255,0.04);
            border: 1px solid transparent;
            padding: 12px 20px;
            border-radius: var(--radius-sm);
            transition: all var(--transition-fast);
        }
        .left ul li a { color: var(--gray-light); font-weight: 500; font-size: 0.95rem; display: block; }
        .left ul li:hover { background: rgba(255,211,0,0.08); border-color: var(--glass-border); }
        .left ul li:hover a { color: var(--gold); }
        .left ul li.active {
            background: var(--gradient-gold);
            box-shadow: var(--shadow-gold);
        }
        .left ul li.active a { color: var(--black); font-weight: 700; }
        .left ul li.active:hover { opacity: 0.9; }
        .right-frame { flex: 1; border: none; }

        @media (max-width: 768px) {
            .left { width: 200px; min-width: 200px; }
            .left ul li { padding: 10px 14px; font-size: 0.85rem; }
        }
        @media (max-width: 480px) {
            .container { flex-direction: column; }
            .left { width: 100%; min-width: unset; flex-direction: row; padding: 12px; overflow-x: auto; }
            .left h1 { display: none; }
            .left ul { flex-direction: row; width: auto; gap: 4px; }
            .left ul li { padding: 8px 14px; white-space: nowrap; }
            .right-frame { height: calc(100vh - 60px); }
        }
    </style>
</head>
<body>
    <div class="container">
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
        <iframe id="content-frame" name="content-frame" class="right-frame" src="dashboard.php"></iframe>
        <script>
            document.querySelectorAll('#admin-nav a').forEach(link => {
                link.addEventListener('click', function() {
                    const page = this.getAttribute('href');
                    document.querySelectorAll('#admin-nav .nav-item').forEach(item => {
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
