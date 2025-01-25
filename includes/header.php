<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        header {
            background:transparent;
            color: #fff;
            padding: 10px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
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
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <nav>
            <div class="logo">
                <img src="../assets/logo.ico" alt="Logo">
            </div>
            <div class="options">
                <a href="../home.php">Home</a>
                <a href="../pages/explore.php">Explore</a>
                <a href="#">Category</a>
                <a href="../pages/profile.php">Profile</a>
            </div>
            <div class="search-section">
                <input type="search" name="searchbar" placeholder="Search Anime">
            </div>
        </nav>
    </header>
</body>

</html>