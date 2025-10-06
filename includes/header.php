<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/logo.ico">
    <title>Header</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        header {
            background-color: black;
            color: #fff;
            padding: 10px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            width: 100%;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .logo img {
            height: 30px;
        }

        .nav-center {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .options {
            display: flex;
            align-items: center;
            gap: 0;
        }

        .options a {
            color: #fff;
            text-decoration: none;
            text-decoration-line: none;
            margin: 0 10px;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
        }

        .options a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .profile-link {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-left: 10px;
            text-decoration: none;
        }

        .profile-link img {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #fff;
        }

        .search-section {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }

        .search-section input {
            padding: 10px;
            border: none;
            border-radius: 5px 0 0 5px;
            background: transparent;
            transition: 0.3s ease-in-out;
        }

        .search-section input::placeholder {
            color: #fff;
        }

        .search-section input:focus {
            outline: none;
        }

        .search-section input:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .search-section input:focus::placeholder {
            color: transparent;
        }

        .search-section button {
            padding: 8px;
            border: none;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            background: transparent;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .search-section button img {
            width: 20px;
            height: 20px;
        }

        .search-section button:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <nav>
            <div class="logo">
                <img src="../assets/logo.ico" alt="Logo">
            </div>
            <div class="nav-center">
                <div class="options">
                    <a href="../home.php">Home</a>
                    <a href="../pages/explore.php">Movies</a>
                    <a href="../pages/watchlist.php">Watchlist</a>
                    <a href="../admin/admin.php">Admin</a>
                </div>
                <?php
                // Show profile icon of the user currently logged in
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $profile_pic_url = '';
                $username = '';
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                    $username = $_SESSION['username'] ?? 'User';
                    if (!empty($_SESSION['profile_picture'])) {
                        $profile_pic_url = "../assets/profile_pics/" . htmlspecialchars($_SESSION['profile_picture']);
                    } else {
                        $profile_pic_url = "https://ui-avatars.com/api/?name=" . urlencode($username) . "&background=222&color=fff";
                    }
                } else {
                    // Not logged in, show default avatar
                    $profile_pic_url = "https://ui-avatars.com/api/?name=User&background=222&color=fff";
                }
                ?>
                <a href="../pages/profile.php" style="display: flex; align-items: center; gap: 6px; text-decoration: none;">
                    <img src="<?php echo $profile_pic_url; ?>" alt="Profile" style="width:28px; height:28px; border-radius:50%; object-fit:cover; border:1px solid #fff;">
                    <span style="color:#fff;"><?php echo htmlspecialchars($username); ?></span>
                </a>
            </div>
            <div class="search-section">
                <input type="search" id="searchQuery" name="searchbar" placeholder="Search Anime">
                <button type="button" onclick="performSearch()">
                    <img src="../assets/icons/search.png" alt="Search">
                </button>
            </div>
        </nav>
    </header>
    <script>
        function performSearch() {
            const query = document.getElementById('searchQuery').value.trim();
            if (query) {
                window.location.href = `../includes/search.php?query=${encodeURIComponent(query)}`;
            } else {
                alert('Please enter a search term.');
            }
        }
    </script>
</body>

</html>
