<?php
// Database credentials
$host = "localhost";
$user = "root"; // Replace with your DB username
$password = ""; // Replace with your DB password
$database = "anime_site"; // Replace with your database name

// Path to save the .sql file
$output_file = __DIR__ . "/Anime-Site/animesite.sql";

// Command to export database
$command = "mysqldump -h $host -u $user --password=$password $database > $output_file";

// Execute the command
exec($command, $output, $result);

if ($result === 0) {
    echo "Database exported successfully to $output_file";
} else {
    echo "Error exporting database.";
}
?>
