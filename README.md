# Anime Site

This project is an Anime and Movie listing site with functionalities to add, update, and delete anime and movies. It also includes user authentication, an admin panel, and a video player for episodes.

## Features

- User Authentication (Login/Signup)
- Admin Panel to manage Anime and Movies
- Add, Update, and Delete Anime and Movies
- List of Episodes for each Anime
- Video Player for Episodes
- Search functionality 
- Explore Anime by Genre
- Responsive Design
- Automatic Database Backup System
- Upcoming Anime Section
- Genre-based Anime Sections (Action, Shounen, Psychological, Seinen)
- Movies Section
- Dashboard with Statistics

## Prerequisites

- XAMPP (Apache, MySQL)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Python 3.x (for database backup system)

## Installation

1. **Clone the repository:**
    ```bash
    git clone https://github.com/your-username/anime-site.git
    cd anime-site
    ```

2. **Start XAMPP:**
    - Open XAMPP Control Panel.
    - Start Apache and MySQL services.

3. **Import the SQL file:**
    - Open phpMyAdmin by navigating to `http://localhost/phpmyadmin`.
    - Create a new database named `anime_site`.
    - Import the `anime_site.sql` file located in the `database` directory.

4. **Configure Database Connection:**
    - Open `includes/dbconnect.php`.
    - Ensure the database credentials match your setup:
      ```php
      <?php 
            $server = "localhost";
            $username = "root";
            $password = "";
            $database = "anime_site";

            $conn = mysqli_connect($server, $username, $password, $database);
            
            if (!$conn){
                 die ("error: " . mysqli_connect_error());
            }
      ?>
      ```

5. **Set up Database Backup System:**
    - Ensure Python 3.x is installed
    - Configure the paths in `database/dbupdate.py`
    - Run the backup script:
      ```bash
      python database/dbupdate.py
      ```

6. **Run the Project:**
    - Open your browser and navigate to `http://localhost/Projects/Anime-Site/home.php`.

## Usage

- **Admin Panel:**
  - Navigate to `http://localhost/Projects/Anime-Site/admin/admin.php`.
  - Use the admin panel to manage anime, movies, and users.
  - View statistics in the dashboard.

- **User Authentication:**
  - Sign up or log in to access the site.
  - Navigate to `http://localhost/Projects/Anime-Site/pages/signup.php` to sign up.
  - Navigate to `http://localhost/Projects/Anime-Site/pages/login.php` to log in.

- **Explore and Search:**
  - Use the explore page to browse anime by genre: `http://localhost/Projects/Anime-Site/pages/explore.php`.
  - Browse specific genre sections (Action, Shounen, Psychological, Seinen).
  - Check out upcoming anime in the Upcoming section.
  - Use the search functionality to find specific anime.

## Contributing

Contributions are welcome! Please fork the repository and create a pull request with your changes.

## License

This project is licensed under the MIT License.
