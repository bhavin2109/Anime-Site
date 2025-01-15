<?php
// Database connection details
$server = "localhost";
$username = "root";
$password = "";
$database = "anime_site";

// Path to save the .sql file
$output_file = __DIR__ . "../database/anime_site.sql";

// Full path to mysqldump (adjust as necessary)
$mysqldump_path = "C:\xampp\mysql\bin\mysqldump"; // Update this path based on your server

// Command to export database
$command = escapeshellcmd("$mysqldump_path -h $server -u $username --password=$password $database > $output_file");

// Execute the command
exec($command, $output, $result);

if ($result === 0) {
    echo "Database exported successfully to $output_file";
} else {
    echo "Error exporting database. Output: " . implode("\n", $output);
}
?>